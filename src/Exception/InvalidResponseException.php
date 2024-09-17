<?php

namespace KeySMS\Exception;

class InvalidResponseException extends Exception
{
    protected int $statusCode = 500;
}
