<?php

namespace KeySMS\Contracts;

interface PhoneNumber
{
    public function getCountryCode(): string;
    public function getNationalPhoneNumber(): string;
    public function getInternationalPhoneNumber(): string;
}
