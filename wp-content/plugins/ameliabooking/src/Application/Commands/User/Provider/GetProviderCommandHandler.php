<?php

namespace AmeliaBooking\Application\Commands\User\Provider;

use AmeliaBooking\Application\Commands\CommandHandler;
use AmeliaBooking\Application\Commands\CommandResult;
use AmeliaBooking\Application\Common\Exceptions\AccessDeniedException;
use AmeliaBooking\Application\Services\User\ProviderApplicationService;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Entities;
use AmeliaBooking\Domain\Entity\User\AbstractUser;
use AmeliaBooking\Domain\Entity\User\Provider;
use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\Common\Exceptions\QueryExecutionException;
use AmeliaBooking\Infrastructure\Services\Google\GoogleCalendarService;
use AmeliaBooking\Infrastructure\Services\Outlook\OutlookCalendarService;
use Interop\Container\Exception\ContainerException;
use Slim\Exception\ContainerValueNotFoundException;

/**
 * Class GetProviderCommandHandler
 *
 * @package AmeliaBooking\Application\Commands\User\Provider
 */
class GetProviderCommandHandler extends CommandHandler
{
    /**
     * @param GetProviderCommand $command
     *
     * @return CommandResult
     * @throws ContainerValueNotFoundException
     * @throws AccessDeniedException
     * @throws QueryExecutionException
     * @throws ContainerException
     * @throws InvalidArgumentException
     */
    public function handle(GetProviderCommand $command)
    {
        /** @var int $providerId */
        $providerId = (int)$command->getField('id');

        /** @var AbstractUser $currentUser */
        $currentUser = $this->container->get('logged.in.user');

        if (!$this->getContainer()->getPermissionsService()->currentUserCanRead(Entities::EMPLOYEES) ||
            (
                !$this->getContainer()->getPermissionsService()->currentUserCanReadOthers(Entities::EMPLOYEES) &&
                $currentUser->getId()->getValue() !== $providerId
            )
        ) {
            throw new AccessDeniedException('You are not allowed to read employee.');
        }

        $result = new CommandResult();

        /** @var ProviderApplicationService $providerService */
        $providerService = $this->container->get('application.user.provider.service');
        /** @var SettingsService $settingsService */
        $settingsService = $this->container->get('domain.settings.service');
        /** @var GoogleCalendarService $googleCalService */
        $googleCalService = $this->container->get('infrastructure.google.calendar.service');
        /** @var OutlookCalendarService $outlookCalendarService */
        $outlookCalendarService = $this->container->get('infrastructure.outlook.calendar.service');

        $companyDaysOff = $settingsService->getCategorySettings('daysOff');
        $companyDayOff = $providerService->checkIfTodayIsCompanyDayOff($companyDaysOff);

        /** @var Provider $provider */
        $provider = $providerService->getProviderWithServicesAndSchedule($providerId);

        if (!$provider instanceof AbstractUser) {
            $result->setResult(CommandResult::RESULT_ERROR);
            $result->setMessage('Could not retrieve user');

            return $result;
        }

        $providerArray = $providerService->manageProvidersActivity(
            [$provider->toArray()],
            $companyDayOff
        )[0];

        $successfulGoogleConnection = true;
        $successfulOutlookConnection = true;

        if ($googleCalService) {
            try {
                // Get Provider's Google Calendar List
                $providerArray['googleCalendar']['calendarList'] = $googleCalService->listCalendarList($provider);

                // Set Provider's Default Google Calendar Id
                $providerArray['googleCalendar']['calendarId'] = $googleCalService->getProviderGoogleCalendarId($provider);
            } catch (\Exception $e) {
                $successfulGoogleConnection = false;
            }
        }

        try {
            // Get Provider's Outlook Calendar List
            $providerArray['outlookCalendar']['calendarList'] = $outlookCalendarService->listCalendarList($provider);

            // Set Provider's Default Google Calendar Id
            $providerArray['outlookCalendar']['calendarId'] = $outlookCalendarService->getProviderOutlookCalendarId(
                $provider
            );
        } catch (\Exception $e) {
            $successfulOutlookConnection = false;
        }

        $result->setResult(CommandResult::RESULT_SUCCESS);
        $result->setMessage('Successfully retrieved user.');
        $result->setData([
            Entities::USER                => $providerArray,
            'successfulGoogleConnection'  => $successfulGoogleConnection,
            'successfulOutlookConnection' => $successfulOutlookConnection,
        ]);

        return $result;
    }
}