<?php

namespace KeySMS\Exception;

use Exception as BaseException;

abstract class Exception extends BaseException
{
    const AUTHENTICATION_EXCEPTION = 'not_authed';
    const CREDENTIALS_NOT_SET_EXCEPTION = 'credentials_not_set';
    const NOT_POSSIBLE_TO_DELETE_ALREADY_SENT_MESSAGE = 'Not possible to delete already sent message';
    const INVALID_SENDER = 'Not a valid sender';
    const INACTIVE_ACCOUNT = 'Account not active';
    const QUOTA_EXCEEDED = 'Account limit exceeded';
    const INVALID_RESPONSE = 'invalid_response';

    const EXCEPTIONS = [
        self::AUTHENTICATION_EXCEPTION => AuthenticationException::class,
        self::CREDENTIALS_NOT_SET_EXCEPTION => CredentialsNotSetException::class,
        self::NOT_POSSIBLE_TO_DELETE_ALREADY_SENT_MESSAGE => IllegalActionException::class,
        self::INVALID_SENDER => InvalidSenderException::class,
        self::INACTIVE_ACCOUNT => InactiveAccountException::class,
        self::QUOTA_EXCEEDED => QuotaExceededException::class,
        self::INVALID_RESPONSE => InvalidResponseException::class,
    ];

    public static function from(?string $code, $response = null): ?BaseException
    {
        if (isset(static::EXCEPTIONS[$code])) {
            $exception = static::EXCEPTIONS;
            return new $exception[$code]($response['error'] ?? null);
        }

        return null;
    }
}
