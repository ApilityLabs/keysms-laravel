<?php

namespace KeySMS\Exception;

class QuotaExceededException extends Exception
{
    public function __construct()
    {
        parent::__construct('Account quota exceeded');
    }
}
