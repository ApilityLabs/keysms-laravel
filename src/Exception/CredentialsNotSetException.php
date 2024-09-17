<?php

namespace KeySMS\Exception;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use KeySMS\Contracts\StatusCode;

class CredentialsNotSetException extends Exception implements Responsable, StatusCode
{
    protected int $statusCode = 500;

    public function __construct()
    {
        parent::__construct('Credentials not set');
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function toResponse($request)
    {
        return response()
            ->json(['ok' => false, 'error' => 'Credentials not set'])
            ->setStatusCode($this->getStatusCode());
    }
}
