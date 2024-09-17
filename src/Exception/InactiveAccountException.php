<?php

namespace KeySMS\Exception;

class InactiveAccountException extends Exception
{
    protected int $statusCode = 403;
}
