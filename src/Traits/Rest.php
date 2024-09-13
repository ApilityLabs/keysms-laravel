<?php

namespace KeySMS\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use KeySMS\Facades\KeySMS;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\ForwardsCalls;

/**
 * @method static static create(array $attributes)
 */
trait Rest
{
    use Attributes;
    use ValidateInput;

    protected static function endpoint(): string
    {
        $instance = new static;

        isset($instance->endpoint)
            ? $instance->endpoint
            : Str::plural(Str::kebab(class_basename(static::class)));

        return Str::plural(Str::kebab(class_basename(static::class)));
    }

    protected static function responseKey(): string
    {
        $instance = new static;

        return isset($instance->key)
            ? $instance->key
            : Str::singular(Str::kebab(class_basename(static::class)));
    }

    public static function find(string $id): ?static
    {
        return new static(
            KeySMS::get(
                sprintf('/%s/%s', static::endpoint(), $id),
            )[static::responseKey()]
        );
    }

    /**
     * @param string $id
     * @throws ModelNotFoundException
     * @return static 
     */
    public static function findOrFail(string $id): static
    {
        if ($contact = static::find($id)) {
            return $contact;
        }

        throw new ModelNotFoundException(class_basename(static::class) . ' not found');
    }

    /**
     * @return Collection<static> 
     */
    public static function all(): Collection
    {
        $key = Str::plural(static::responseKey());

        return Collection::make(
            array_values(
                KeySMS::get(
                    sprintf('/%s', static::endpoint()),
                )[$key]
            )
        );
    }

    public function save(): bool
    {
        if ($this->exists) {
            return $this->update($this->attributes);
        }

        if (!$this->validateInput($this->attributes)) {
            return false;
        }

        $this->attributes = KeySMS::post(
            sprintf('/%s', static::endpoint()),
            $this->attributes
        )[static::responseKey()];

        $this->isDirty = false;

        return true;
    }

    public static function create(array $attributes = []): static
    {
        $instance = new static($attributes);

        if (!$instance->save()) {
            throw new Exception('Failed to create ' . class_basename(static::class));
        }

        return $instance;
    }

    public function update(array $attributes = []): bool
    {
        if (!$this->validateInput($attributes)) {
            return false;
        }

        $this->attributes = KeySMS::put(
            sprintf('/%s/%s', static::endpoint(), $this->id),
            $attributes
        )[static::responseKey()];

        $this->isDirty = false;

        return true;
    }

    public function delete(): bool
    {
        if ($this->exists) {
            KeySMS::delete(
                sprintf('/%s/%s', static::endpoint(), $this->id)
            );

            return true;
        }

        return false;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->id;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param string  $value
     * @param string|null  $field
     * @return static|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field && $field !== 'id') {
            return null;
        }

        return static::find($value);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param string $childType
     * @param mixed $value
     * @param string|null $field
     * @return null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return null;
    }
}
