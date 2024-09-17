<?php

namespace KeySMS\Exception;

class QuotaExceededException extends Exception
{
    protected int $statusCode = 402;
}
