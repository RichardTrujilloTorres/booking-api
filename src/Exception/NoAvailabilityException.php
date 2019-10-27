<?php

namespace App\Exception;

/**
 * Class NoAvailabilityException
 * @package App\Exception
 */
class NoAvailabilityException extends \Exception
{
    protected $message = 'There\'s no availability for the specified time period.';

    protected $code = 400;
}
