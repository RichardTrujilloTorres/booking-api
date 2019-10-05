<?php

namespace App\Exception;

/**
 * Class InvalidTimeException
 * @package App\Exception
 */
class InvalidTimeException extends \Exception
{
    protected $message = 'Invalid time specified.';

    protected $code = 422;
}
