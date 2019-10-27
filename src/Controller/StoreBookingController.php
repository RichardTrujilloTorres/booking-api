<?php

namespace App\Controller;

use ApiPlatform\Core\Exception\InvalidResourceException;
use App\Entity\Booking;
use App\Exception\InvalidTimeException;
use App\Exception\NoAvailabilityException;
use App\Exception\NoTimesMatchException;
use App\Repository\BookingRepository;
use App\Service\BookingService;
use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StoreBookingController
 * @package App\Controller
 */
class StoreBookingController
{
    private $bookingRepository;
    private $request;
    private $bookingService;

    public function __construct(
        BookingRepository $bookingRepository,
        RequestStack $requestStack,
        BookingService $bookingService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->bookingService = $bookingService;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return bool
     */
    protected function validateTimes(DateTime $startTime, DateTime $endTime)
    {
        if (
            (! $startTime || ! $endTime) ||
            (! ($endTime > $startTime)) ||
            ($endTime->diff($startTime)->h < 1) ||
            ($endTime->format('Y-m-d') !== $startTime->format('Y-m-d'))
        ) {
            return false;
        }

        return true;
    }

    public function __invoke()
    {
        $content = json_decode($this->request->getContent());

        $startTime = DateTime::createFromFormat('Y-m-d H:i', $content->startTime);
        $endTime = DateTime::createFromFormat('Y-m-d H:i', $content->endTime);
        $tableNumber = $content->tableNumber;

        if (! $this->validateTimes($startTime, $endTime)) {
            throw new InvalidTimeException;
        }

        if (! $this->bookingService->hasAvailability($startTime, $tableNumber)) {
            throw new NoAvailabilityException;
        }

        // TODO check table number validity
        // http client
        // call to the restaurant app service
        // looks for the availability
        // locks-down the booking for a predefined amount of time (if payment necessary to process further)
        // creates the booking


        // TODO check availability
        $booking = (new Booking())
            ->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setTableNumber($tableNumber);

        return $booking;
    }
}
