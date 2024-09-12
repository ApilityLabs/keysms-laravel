<?php

namespace KeySMS;

use Stringable;

use KeySMS\Traits\Rest;
use KeySMS\Contracts\PhoneNumber as PhoneNumberContract;

use Brick\PhoneNumber\PhoneNumber;

use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * @property ?string $name
 * @property-read ?string $phone
 */
class Contact implements Stringable, PhoneNumberContract, UrlRoutable, Jsonable
{
    use Rest;

    public function getNameAttribute($value): ?string
    {
        if (!is_array($value)) {
            return null;
        }

        return trim(sprintf('%s %s', $value['first'] ?? null, $value['last'] ?? null));
    }

    public function setNameAttribute(string $value)
    {
        $parts = explode(' ', $value);
        $first = array_shift($parts);
        $last = implode(' ', $parts);

        return ['first' => $first, 'last' => $last];
    }

    public function getPhoneAttribute(): ?string
    {
        $prefix = $this->getCountryCode();
        $number = $this->getNationalPhoneNumber();

        return implode('', ["+$prefix", $number]);
    }

    public function setPhoneAttribute(string $value)
    {
        $phone = PhoneNumber::parse($value, 'NO');
        $prefix = $phone->getCountryCode();
        $number = $phone->getNationalNumber();

        $this->attributes['phones'] = [['prefix' => $prefix, 'number' => $number]];
    }

    public function message(string $message): PendingSMS
    {
        return SMS::to($this)
            ->message($message);
    }

    public function getCountryCode(): string
    {
        return data_get($this->attributes, 'phones.0.prefix', '47');
    }

    public function getNationalPhoneNumber(): string
    {
        return data_get($this->attributes, 'phones.0.number');
    }

    public function getInternationalPhoneNumber(): string
    {
        return $this->phone;
    }

    public function __toString(): string
    {
        return $this->phone;
    }
}
