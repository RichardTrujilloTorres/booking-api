<?php

namespace App\Controller;

use ApiPlatform\Core\Exception\InvalidResourceException;
use App\Entity\Booking;
use App\Exception\InvalidTimeException;
use App\Exception\NoTimesMatchException;
use App\Repository\BookingRepository;
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
    private $startTime;
    private $endTime;


    public function __construct(
        BookingRepository $bookingRepository,
        RequestStack $requestStack
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->request = $requestStack->getCurrentRequest();
        $this->startTime = $requestStack->getCurrentRequest()->request->get('start_time');
        $this->endTime = $requestStack->getCurrentRequest()->request->get('endTime');
    }

    public function __invoke()
    {
        $content = json_decode($this->request->getContent());

        $startTime = DateTime::createFromFormat('H:i', $content->startTime);
        $endTime = DateTime::createFromFormat('H:i', $content->endTime);
        $tableNumber = $content->tableNumber;

        // TODO check table number validity

        if (
            (! $startTime || ! $endTime) ||
            (! ($endTime > $startTime)) ||
            ($endTime->diff($startTime)->h < 1)
        ) {
            throw new InvalidTimeException;
        }

        // TODO process the booking

        $booking = (new Booking())
            ->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setTableNumber($tableNumber);

        return $booking;
    }
}
