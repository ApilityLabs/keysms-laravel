<?php

namespace KeySMS\Exception;

class InvalidReceiversException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid receiver(s)');
    }
}
