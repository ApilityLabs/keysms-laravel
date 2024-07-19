<?php

namespace KeySMS\Exception;

use Exception as BaseException;

class UnknownException extends BaseException
{
    public function __construct(string $message = null)
    {
        parent::__construct($message ?? 'Unknown error');
    }
}