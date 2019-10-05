<?php

namespace App\Exception;

/**
 * Class NoTimesMatchException
 * @package App\Exception
 */
class NoTimesMatchException extends \Exception
{
    protected $message = 'Start time and end time do not match.';

    protected $code = 422;
}
