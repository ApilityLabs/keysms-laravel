<?php

namespace KeySMS\Exception;

class InvalidSenderException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid sender');
    }
}
