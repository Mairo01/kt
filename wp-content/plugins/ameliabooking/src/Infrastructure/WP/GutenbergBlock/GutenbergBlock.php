<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\WP\GutenbergBlock;

use AmeliaBooking\Application\Services\Bookable\BookableApplicationService;
use AmeliaBooking\Application\Services\User\ProviderApplicationService;
use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Entity\User\Provider;
use AmeliaBooking\Domain\Factory\Bookable\Service\ServiceFactory;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Infrastructure\Common\Container;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\CategoryRepository;
use AmeliaBooking\Infrastructure\Repository\Bookable\Service\ServiceRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\EventRepository;
use AmeliaBooking\Infrastructure\Repository\Booking\Event\EventTagsRepository;
use AmeliaBooking\Infrastructure\Repository\Location\LocationRepository;
use Exception;
use Interop\Container\Exception\ContainerException;

/**
 * Class GutenbergBlock
 *
 * @package AmeliaBooking\Infrastructure\WP\GutenbergBlock
 */
class GutenbergBlock
{
    /** @var Container $container */
    private static $container;

    /**
     * Register WP Ajax actions.
     */
    public static function init()
    {
        if (is_admin() && function_exists('register_block_type')) {
            if (substr($_SERVER['PHP_SELF'], '-8') == 'post.php' ||
                substr($_SERVER['PHP_SELF'], '-12') == 'post-new.php'
            ) {
                if (self::isGutenbergActive()) {
                    $class = get_called_class();
                    add_action('enqueue_block_editor_assets', function () use ($class) {
                        $class::registerBlockType();
                    });
                }

            }
        }
    }

    /**
     * Register block for gutenberg
     */
    public static function registerBlockType()
    {

    }

    /**
     * Check if Block Editor is active.
     *
     * @return bool
     */
    public static function isGutenbergActive()
    {
        // Gutenberg plugin is installed and activated.
        $gutenberg = !(false === has_filter('replace_editor', 'gutenberg_init'));

        // Block editor since 5.0.
        $block_editor = version_compare($GLOBALS['wp_version'], '5.0-beta', '>');

        if (!$gutenberg && !$block_editor) {
            return false;
        }

        if (self::isClassicEditorPluginActive()) {
            $editor_option = get_option('classic-editor-replace');
            $block_editor_active = array('no-replace', 'block');

            return in_array($editor_option, $block_editor_active, true);
        }

        // Fix for conflict with Avada - Fusion builder and gutenberg blocks
        if (class_exists('FusionBuilder') && !(isset($_GET['gutenberg-editor']))) {
            return false;
        }

        // Fix for conflict with Disable Gutenberg plugin
        if (class_exists('DisableGutenberg')) {
            return false;
        }

        // Fix for conflict with WP Bakery Page Builder
        if (class_exists('Vc_Manager') && (isset($_GET['classic-editor']))) {
            return false;
        }

        // Fix for conflict with WooCommerce product page
        if (isset($_GET['post_type']) && $_GET['post_type'] === 'product' && class_exists('WooCommerce')) {
            return false;
        }

        return true;
    }

    /**
     * Check if Classic Editor plugin is active
     *
     * @return bool
     */
    public static function isClassicEditorPluginActive()
    {

        if (!function_exists('is_plugin_active')) {

            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (is_plugin_active('classic-editor/classic-editor.php')) {

            return true;
        }

        return false;
    }

    /**
     * Set Amelia Container
     *
     * @param $container
     */
    public static function setContainer($container)
    {
        self::$container = $container;
    }

    /**
     * Get entities data for front-end
     */
    public static function getEntitiesData()
    {
        return (new self)->getAllEntitiesForGutenbergBlocks();
    }

    /**
     * Get Entities for Gutenberg blocks
     */
    public function getAllEntitiesForGutenbergBlocks()
    {
        try {
            self::setContainer(require AMELIA_PATH . '/src/Infrastructure/ContainerConfig/container.php');

            /** @var LocationRepository $locationRepository */
            $locationRepository = self::$container->get('domain.locations.repository');

            $locations = $locationRepository->getAllOrderedByName();

            $resultData['locations'] = $locations->toArray();

            /** @var ServiceRepository $serviceRepository */
            $serviceRepository = self::$container->get('domain.bookable.service.repository');
            /** @var CategoryRepository $categoryRepository */
            $categoryRepository = self::$container->get('domain.bookable.category.repository');
            /** @var BookableApplicationService $bookableAS */
            $bookableAS = self::$container->get('application.bookable.service');

            $services = $serviceRepository->getAllArrayIndexedById();

            $categories = $categoryRepository->getAllIndexedById();

            $bookableAS->addServicesToCategories($categories, $services);

            $resultData['categories'] = $categories->toArray();

            $providerRepository = self::$container->get('domain.users.providers.repository');

            /** @var ProviderApplicationService $providerAS */
            $providerAS = self::$container->get('application.user.provider.service');

            /** @var Collection $providers */
            $providers = $providerRepository->getByCriteriaWithSchedule([]);

            $providerServicesData = $providerRepository->getProvidersServices();

            foreach ((array)$providerServicesData as $providerKey => $providerServices) {
                $provider = $providers->getItem($providerKey);

                $providerServiceList = new Collection();

                foreach ((array)$providerServices as $serviceKey => $providerService) {
                    $service = $services->getItem($serviceKey);

                    if ($service && $provider) {
                        $providerServiceList->addItem(
                            ServiceFactory::create(array_merge($service->toArray(), $providerService)),
                            $service->getId()->getValue()
                        );
                    }
                }

                $provider->setServiceList($providerServiceList);
            }

            /** @var Provider $currentUser */
            $currentUser = self::$container->get('logged.in.user');

            $resultData['employees'] = $providerAS->removeAllExceptUser(
                $providers->toArray(),
                $currentUser
            );

            $finalData = self::getOnlyCatSerLocEmp($resultData);

            /** @var EventRepository $eventRepository */
            $eventRepository = self::$container->get('domain.booking.event.repository');

            /** @var Collection $events */
            $events = $eventRepository->getFiltered(['dates' => [DateTimeService::getNowDateTime()]]);

            $finalData['events'] = $events->toArray();

            /** @var EventTagsRepository $eventTagsRepository */
            $eventTagsRepository = self::$container->get('domain.booking.event.tag.repository');

            /** @var Collection $tags * */
            $tags = $eventTagsRepository->getAllDistinctByCriteria(
                [
                    'eventIds' => array_column($finalData['events'], 'id')
                ]
            );

            $finalData['tags'] = $tags->toArray();

            return ['data' => $finalData];

        } catch (Exception $exception) {
            return ['data' => [
                'categories'   => [],
                'servicesList' => [],
                'locations'    => [],
                'employees'    => [],
                'events'       => [],
                'tags'         => []
            ]];
        } catch (ContainerException $e) {
            return ['data' => [
                'categories'   => [],
                'servicesList' => [],
                'locations'    => [],
                'employees'    => [],
                'events'       => [],
                'tags'         => []
            ]];
        }
    }

    /**
     * Get only Categories, Services, Employees and Locations for Gutenberg blocks
     */
    public static function getOnlyCatSerLocEmp($resultData)
    {
        $data = [];
        $data['categories'] = [];
        $data['servicesList'] = [];
        if ($resultData['categories'] !== []) {
            for ($i = 0; $i < count($resultData['categories']); $i++) {
                $data['categories'][] = [
                    'id'   => $resultData['categories'][$i]['id'],
                    'name' => $resultData['categories'][$i]['name']
                ];
                if ($resultData['categories'][$i]['serviceList'] !== []) {

                    for ($j = 0; $j < count($resultData['categories'][$i]['serviceList']); $j++) {

                        if (!$resultData['categories'][$i]['serviceList'][$j]['show']) {
                            continue;
                        }

                        $data['servicesList'][] = [
                            'id'   => $resultData['categories'][$i]['serviceList'][$j]['id'],
                            'name' => $resultData['categories'][$i]['serviceList'][$j]['name']
                        ];
                    }
                } else {
                    $data['servicesList'][$i] = [];
                }

            }
        } else {
            $data['categories'] = [];
            $data['servicesList'] = [];
        }

        if ($resultData['locations'] !== []) {
            for ($i = 0; $i < count($resultData['locations']); $i++) {
                $data['locations'][] = [
                    'id'   => $resultData['locations'][$i]['id'],
                    'name' => $resultData['locations'][$i]['name']
                ];
            }
        } else {
            $data['locations'] = [];
        }

        if ($resultData['employees'] !== []) {
            for ($i = 0; $i < count($resultData['employees']); $i++) {
                $data['employees'][] = [
                    'id'        => $resultData['employees'][$i]['id'],
                    'firstName' => $resultData['employees'][$i]['firstName'],
                    'lastName'  => $resultData['employees'][$i]['lastName'],
                ];
            }
        } else {
            $data['employees'] = [];
        }

        return $data;
    }
}
