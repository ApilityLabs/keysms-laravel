<?php

namespace KeySMS\Exception;

class InvalidSenderException extends Exception
{
    protected int $statusCode = 400;
}
