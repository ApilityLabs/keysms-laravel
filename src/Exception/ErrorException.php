<?php

namespace KeySMS\Exception;

class ErrorException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 0);
    }
}
