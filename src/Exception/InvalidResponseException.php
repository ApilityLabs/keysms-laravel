<?php

namespace KeySMS\Exception;

class InvalidResponseException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid response');
    }
}
