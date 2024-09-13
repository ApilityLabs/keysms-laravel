<?php

namespace KeySMS\Exception;

use Exception;
use Illuminate\Contracts\Support\Responsable;

class CredentialsNotSetException extends Exception implements Responsable
{
    public function __construct()
    {
        parent::__construct('Credentials not set');
    }

    public function toResponse($request)
    {
        return response()
            ->json(['ok' => false, 'error' => 'Credentials not set'])
            ->setStatusCode(401);
    }
}
