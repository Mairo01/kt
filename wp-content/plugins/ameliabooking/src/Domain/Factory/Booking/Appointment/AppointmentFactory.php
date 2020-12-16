<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Domain\Factory\Booking\Appointment;

use AmeliaBooking\Domain\Collection\Collection;
use AmeliaBooking\Domain\Common\Exceptions\InvalidArgumentException;
use AmeliaBooking\Domain\Entity\Booking\Appointment\Appointment;
use AmeliaBooking\Domain\Factory\Bookable\Service\ServiceFactory;
use AmeliaBooking\Domain\Factory\User\UserFactory;
use AmeliaBooking\Domain\Factory\Zoom\ZoomFactory;
use AmeliaBooking\Domain\Services\DateTime\DateTimeService;
use AmeliaBooking\Domain\ValueObjects\BooleanValueObject;
use AmeliaBooking\Domain\ValueObjects\DateTime\DateTimeValue;
use AmeliaBooking\Domain\ValueObjects\Number\Integer\Id;
use AmeliaBooking\Domain\ValueObjects\String\BookingStatus;
use AmeliaBooking\Domain\ValueObjects\String\Description;
use AmeliaBooking\Domain\ValueObjects\String\Token;

/**
 * Class AppointmentFactory
 *
 * @package AmeliaBooking\Domain\Factory\Booking\Appointment
 */
class AppointmentFactory
{

    /**
     * @param $data
     *
     * @return Appointment
     * @throws InvalidArgumentException
     */
    public static function create($data)
    {
        $appointment = new Appointment(
            new DateTimeValue(DateTimeService::getCustomDateTimeObject($data['bookingStart'])),
            new DateTimeValue(DateTimeService::getCustomDateTimeObject($data['bookingEnd'])),
            $data['notifyParticipants'],
            new Id($data['serviceId']),
            new Id($data['providerId'])
        );

        if (isset($data['id'])) {
            $appointment->setId(new Id($data['id']));
        }

        if (isset($data['parentId'])) {
            $appointment->setParentId(new Id($data['parentId']));
        }

        if (isset($data['locationId'])) {
            $appointment->setLocationId(new Id($data['locationId']));
        }

        if (isset($data['internalNotes'])) {
            $appointment->setInternalNotes(new Description($data['internalNotes']));
        }

        if (isset($data['status'])) {
            $appointment->setStatus(new BookingStatus($data['status']));
        }

        if (isset($data['provider'])) {
            $appointment->setProvider(UserFactory::create($data['provider']));
        }

        if (isset($data['service'])) {
            $appointment->setService(ServiceFactory::create($data['service']));
        }

        if (!empty($data['googleCalendarEventId'])) {
            $appointment->setGoogleCalendarEventId(new Token($data['googleCalendarEventId']));
        }

        if (!empty($data['outlookCalendarEventId'])) {
            $appointment->setOutlookCalendarEventId(new Token($data['outlookCalendarEventId']));
        }

        if (!empty($data['zoomMeeting']['id'])) {
            $zoomMeeting = ZoomFactory::create(
                $data['zoomMeeting']
            );

            $appointment->setZoomMeeting($zoomMeeting);
        }

        if (isset($data['isRescheduled'])) {
            $appointment->setRescheduled(new BooleanValueObject($data['isRescheduled']));
        }

        $bookings = new Collection();

        if (isset($data['bookings'])) {
            foreach ((array)$data['bookings'] as $key => $value) {
                $bookings->addItem(
                    CustomerBookingFactory::create($value),
                    $key
                );
            }
        }

        $appointment->setBookings($bookings);

        return $appointment;
    }

    /**
     * @param array $rows
     *
     * @return Collection
     * @throws InvalidArgumentException
     */
    public static function createCollection($rows)
    {
        $appointments = [];

        foreach ($rows as $row) {
            $appointmentId = $row['appointment_id'];
            $bookingId = isset($row['booking_id']) ? $row['booking_id'] : null;
            $bookingExtraId = isset($row['bookingExtra_id']) ? $row['bookingExtra_id'] : null;
            $paymentId = isset($row['payment_id']) ? $row['payment_id'] : null;
            $couponId = isset($row['coupon_id']) ? $row['coupon_id'] : null;
            $customerId = isset($row['customer_id']) ? $row['customer_id'] : null;
            $providerId = isset($row['provider_id']) ? $row['provider_id'] : null;
            $serviceId = isset($row['service_id']) ? $row['service_id'] : null;

            if (!array_key_exists($appointmentId, $appointments)) {
                $zoomMeetingJson = !empty($row['appointment_zoom_meeting']) ?
                    json_decode($row['appointment_zoom_meeting'], true) : null;

                $appointments[$appointmentId] = [
                    'id'                     => $appointmentId,
                    'parentId'               => isset($row['appointment_parentId']) ?
                        $row['appointment_parentId'] : null,
                    'bookingStart'           => DateTimeService::getCustomDateTimeFromUtc(
                        $row['appointment_bookingStart']
                    ),
                    'bookingEnd'             => DateTimeService::getCustomDateTimeFromUtc(
                        $row['appointment_bookingEnd']
                    ),
                    'notifyParticipants'     => isset($row['appointment_notifyParticipants']) ?
                        $row['appointment_notifyParticipants'] : null,
                    'serviceId'              => $row['appointment_serviceId'],
                    'providerId'             => $row['appointment_providerId'],
                    'locationId'             => isset($row['appointment_locationId']) ?
                        $row['appointment_locationId'] : null,
                    'internalNotes'          => isset($row['appointment_internalNotes']) ?
                        $row['appointment_internalNotes'] : null,
                    'status'                 => $row['appointment_status'],
                    'googleCalendarEventId'  => $row['appointment_google_calendar_event_id'],
                    'outlookCalendarEventId' => $row['appointment_outlook_calendar_event_id'],
                    'zoomMeeting'            => [
                        'id'       => $zoomMeetingJson ? $zoomMeetingJson['id'] : null,
                        'startUrl' => $zoomMeetingJson ? $zoomMeetingJson['startUrl'] : null,
                        'joinUrl'  => $zoomMeetingJson ? $zoomMeetingJson['joinUrl'] : null,
                    ],
                ];
            }

            if ($bookingId && !isset($appointments[$appointmentId]['bookings'][$bookingId])) {
                $appointments[$appointmentId]['bookings'][$bookingId] = [
                    'id'              => $bookingId,
                    'appointmentId'   => $appointmentId,
                    'customerId'      => $row['booking_customerId'],
                    'status'          => $row['booking_status'],
                    'price'           => $row['booking_price'],
                    'persons'         => $row['booking_persons'],
                    'customFields'    => isset($row['booking_customFields']) ? $row['booking_customFields'] : null,
                    'info'            => isset($row['booking_info']) ? $row['booking_info'] : null,
                    'utcOffset'       => isset($row['booking_utcOffset']) ? $row['booking_utcOffset'] : null,
                    'aggregatedPrice' => isset($row['booking_aggregatedPrice']) ?
                        $row['booking_aggregatedPrice'] : null,
                ];
            }

            if ($bookingId && $bookingExtraId) {
                $appointments[$appointmentId]['bookings'][$bookingId]['extras'][$bookingExtraId] =
                    [
                        'id'                => $bookingExtraId,
                        'customerBookingId' => $bookingId,
                        'extraId'           => $row['bookingExtra_extraId'],
                        'quantity'          => $row['bookingExtra_quantity'],
                        'price'             => $row['bookingExtra_price'],
                        'aggregatedPrice'   => $row['bookingExtra_aggregatedPrice']
                    ];
            }

            if ($bookingId && $paymentId) {
                $appointments[$appointmentId]['bookings'][$bookingId]['payments'][$paymentId] =
                    [
                        'id'                => $paymentId,
                        'customerBookingId' => $bookingId,
                        'status'            => $row['payment_status'],
                        'dateTime'          => DateTimeService::getCustomDateTimeFromUtc($row['payment_dateTime']),
                        'gateway'           => $row['payment_gateway'],
                        'gatewayTitle'      => $row['payment_gatewayTitle'],
                        'amount'            => $row['payment_amount'],
                        'data'              => $row['payment_data'],
                    ];
            }

            if ($bookingId && $couponId) {
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['id'] = $couponId;
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['code'] = $row['coupon_code'];
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['discount'] = $row['coupon_discount'];
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['deduction'] = $row['coupon_deduction'];
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['limit'] = $row['coupon_limit'];
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['customerLimit'] = $row['coupon_customerLimit'];
                $appointments[$appointmentId]['bookings'][$bookingId]['coupon']['status'] = $row['coupon_status'];
            }

            if ($bookingId && $customerId) {
                $appointments[$appointmentId]['bookings'][$bookingId]['customer'] =
                    [
                        'id'        => $customerId,
                        'firstName' => $row['customer_firstName'],
                        'lastName'  => $row['customer_lastName'],
                        'email'     => $row['customer_email'],
                        'note'      => $row['customer_note'],
                        'phone'     => $row['customer_phone'],
                        'gender'    => $row['customer_gender'],
                        'type'      => 'customer',
                    ];
            }

            if ($bookingId && $providerId) {
                $appointments[$appointmentId]['provider'] =
                    [
                        'id'        => $providerId,
                        'firstName' => $row['provider_firstName'],
                        'lastName'  => $row['provider_lastName'],
                        'email'     => $row['provider_email'],
                        'note'      => $row['provider_note'],
                        'phone'     => $row['provider_phone'],
                        'gender'    => $row['provider_gender'],
                        'type'      => 'provider',
                    ];
            }

            if ($serviceId) {
                $appointments[$appointmentId]['service']['id'] = $row['service_id'];
                $appointments[$appointmentId]['service']['name'] = $row['service_name'];
                $appointments[$appointmentId]['service']['description'] = $row['service_description'];
                $appointments[$appointmentId]['service']['color'] = $row['service_color'];
                $appointments[$appointmentId]['service']['price'] = $row['service_price'];
                $appointments[$appointmentId]['service']['status'] = $row['service_status'];
                $appointments[$appointmentId]['service']['categoryId'] = $row['service_categoryId'];
                $appointments[$appointmentId]['service']['minCapacity'] = $row['service_minCapacity'];
                $appointments[$appointmentId]['service']['maxCapacity'] = $row['service_maxCapacity'];
                $appointments[$appointmentId]['service']['duration'] = $row['service_duration'];
                $appointments[$appointmentId]['service']['timeBefore'] = isset($row['service_timeBefore'])
                    ? $row['service_timeBefore'] : null;
                $appointments[$appointmentId]['service']['timeAfter'] = isset($row['service_timeAfter'])
                    ? $row['service_timeAfter'] : null;
                $appointments[$appointmentId]['service']['aggregatedPrice'] = isset($row['service_aggregatedPrice'])
                    ? $row['service_aggregatedPrice'] : null;
                $appointments[$appointmentId]['service']['settings'] = isset($row['service_settings'])
                    ? $row['service_settings'] : null;
            }
        }

        $collection = new Collection();

        foreach ($appointments as $key => $value) {
            $collection->addItem(
                self::create($value),
                $key
            );
        }

        return $collection;
    }
}