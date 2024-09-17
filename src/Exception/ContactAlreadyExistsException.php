<?php

namespace KeySMS\Exception;

class ContactAlreadyExistsException extends Exception
{
    protected int $statusCode = 409;
}
