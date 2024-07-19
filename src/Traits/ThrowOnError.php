<?php

namespace KeySMS\Traits;

use Closure;
use KeySMS\Exception\Exception;
use Illuminate\Http\Client\Response;
use KeySMS\Exception\ErrorException;
use KeySMS\Exception\InvalidResponseException;

trait ThrowOnError
{
    protected function throwOnError(Response $response, ?Closure $validate = null): array
    {
        $data = $response->json();

        if (!$data) {
            throw new InvalidResponseException((string) $response->body());
        }

        if ($validate === null) {
            $validate = fn(array $data) => !$data['ok'];
        }

        if ($validate($data)) {
            $message = data_get($data, 'errors.0', data_get($data, 'error', null));
            if ($exception = Exception::from($message, $data)) {
                throw $exception;
            } else {
                throw new ErrorException($message ?? 'An error occurred', $data);
            }
        }

        return $data;
    }
}
