<?php

namespace KeySMS\Traits;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Facades\Validator;

trait ValidateInput
{
    protected ?MessageBag $errors = null;

    public function validateInput(array $input): bool
    {
        if (isset($this->rules)) {
            $validator = Validator::make($input, $this->rules);

            if ($validator->fails()) {
                $this->errors = $validator->errors();
                return false;
            }
        }

        return true;
    }
}
