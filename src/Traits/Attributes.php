<?php

namespace KeySMS\Traits;

use DateTime;
use DateTimeInterface;

/**
 * @property-read string|null $id
 * @property-read bool $exists
 * @property-read DateTimeInterface|null $created
 * @property-read DateTimeInterface|null $updated
 */
trait Attributes
{
    protected string $primaryKey = '_id';
    protected array $attributes = [];
    protected array $dates = ['created', 'updated'];
    protected bool $isDirty = false;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($name)
    {
        $value = $this->attributes[$name] ?? null;;

        if (property_exists($this, 'dates') && in_array($name, $this->dates)) {
            if (isset($value)) {
                $value = new DateTime($$value);
            }
        }

        if (method_exists($this, 'get' . $name . 'Attribute')) {
            return $this->{'get' . $name . 'Attribute'}($value);
        }

        return $value;
    }

    public function __set($name, $value)
    {
        if (property_exists($this, 'dates') && in_array($name, $this->dates)) {
            if ($value instanceof DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }
        }

        $oldValue = $this->attributes ?? [];
        $newValue = null;

        if (method_exists($this, 'set' . $name . 'Attribute')) {
            $newValue = $this->{'set' . $name . 'Attribute'}($value);
        }

        if ($newValue) {
            $this->attributes[$name] = $newValue;
        }

        if (json_encode($oldValue) !== json_encode($this->attributes)) {
            $this->isDirty = true;
        }
    }

    public function getIdAttribute(): ?string
    {
        return $this->attributes[$this->primaryKey] ?? null;
    }

    public function getExistsAttribute(): bool
    {
        return isset($this->attributes[$this->primaryKey]);
    }

    public function toJson($options = 0)
    {
        return json_encode($this->attributes, $options);
    }
}
