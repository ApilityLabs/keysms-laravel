<?php

namespace KeySMS\Exception;

use Exception as BaseException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Client\Response;

abstract class Exception extends BaseException implements Responsable
{
    const AUTHENTICATION_EXCEPTION = 'not_authed';
    const CREDENTIALS_NOT_SET_EXCEPTION = 'credentials_not_set';
    const NOT_POSSIBLE_TO_DELETE_ALREADY_SENT_MESSAGE = 'Not possible to delete already sent message';
    const INVALID_SENDER = 'Not a valid sender';
    const INACTIVE_ACCOUNT = 'Account not active';
    const QUOTA_EXCEEDED = 'Account limit exceeded';
    const INVALID_RESPONSE = 'invalid_response';
    const INVALID_RECEIVERS = 'message_no_valid_receivers';
    const CONTACT_ALREADY_EXISTS = '11000';

    const EXCEPTIONS = [
        self::AUTHENTICATION_EXCEPTION => AuthenticationException::class,
        self::CREDENTIALS_NOT_SET_EXCEPTION => CredentialsNotSetException::class,
        self::NOT_POSSIBLE_TO_DELETE_ALREADY_SENT_MESSAGE => IllegalActionException::class,
        self::INVALID_SENDER => InvalidSenderException::class,
        self::INACTIVE_ACCOUNT => InactiveAccountException::class,
        self::QUOTA_EXCEEDED => QuotaExceededException::class,
        self::INVALID_RESPONSE => InvalidResponseException::class,
        self::INVALID_RECEIVERS => InvalidReceiversException::class,
        self::CONTACT_ALREADY_EXISTS => ContactAlreadyExistsException::class,
    ];

    public function __construct(protected Response $response)
    {
        parent::__construct(class_basename(static::class));
    }

    public static function from(Response $response): ?BaseException
    {
        $code = static::parseResponseErrorCode($response);

        if (isset(static::EXCEPTIONS[$code])) {
            $exception = static::EXCEPTIONS;
            return new $exception[$code]($response, $code);
        }

        return new ErrorException($response);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function toResponse($request)
    {
        return response()
            ->json($this->response->json())
            ->setStatusCode($this->response->status());
    }

    /**
     * @throws BaseException 
     */
    protected static function parseResponseErrorCode(Response $response): string
    {
        $data = $response->json();

        if (isset($data['debug']['exceptionCode'])) {
            return $data['debug']['exceptionCode'];
        }

        if (isset($data['error'])) {
            foreach ($data['error'] as $error) {
                if (isset($error['code'])) {
                    return $error['code'];
                }
            }
        }

        throw new BaseException('Unable to parse error code from response');
    }
}
