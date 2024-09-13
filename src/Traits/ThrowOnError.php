<?php

namespace KeySMS\Traits;

use Closure;
use KeySMS\Exception\Exception;
use Illuminate\Http\Client\Response;

trait ThrowOnError
{
    protected function throwOnError(Response $response, ?Closure $validate = null): array
    {
        if (!$this->responseOk($response)) {
            throw Exception::from($response);
        }

        return $response->json();
    }

    protected function responseOk(Response $response): bool
    {
        if (!$response->ok()) {
            return false;
        }

        if ($data = $response->json()) {
            if (isset($data['ok']) && $data['ok'] === false) {
                return false;
            }
        }

        return true;
    }
}
