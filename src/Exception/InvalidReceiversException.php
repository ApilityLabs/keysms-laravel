<?php

namespace KeySMS\Exception;

class InvalidReceiversException extends Exception
{
    protected int $statusCode = 400;
}
