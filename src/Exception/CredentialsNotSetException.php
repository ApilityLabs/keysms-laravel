<?php

namespace KeySMS\Exception;

class CredentialsNotSetException extends Exception
{
    public function __construct()
    {
        parent::__construct('Credentials is not set');
    }
}