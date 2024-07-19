<?php

namespace KeySMS\Exception;

class IllegalActionException extends Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message ?? 'Illegal action');
    }
}
