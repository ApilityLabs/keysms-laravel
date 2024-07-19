<?php

namespace KeySMS\Exception;

class InactiveAccountException extends Exception
{
    public function __construct()
    {
        parent::__construct('Inactive account');
    }
}
