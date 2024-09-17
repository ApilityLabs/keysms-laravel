<?php

namespace KeySMS\Exception;

class AuthenticationException extends Exception
{
    protected int $statusCode = 401;
}
