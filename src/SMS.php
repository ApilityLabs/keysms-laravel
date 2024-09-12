<?php

namespace KeySMS;

use DateTime;
use DateTimeInterface;

use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Contracts\Support\Jsonable;

use KeySMS\Facades\KeySMS;
use KeySMS\Traits\Attributes;

/**
 * @property-read array $cost
 * @property-read array $groups
 * @property-read bool $sent
 * @property-read bool $future
 * @property-read array $parts
 * @property-read array $status
 * @property-read string|null $message
 * @property-read string|null $sender
 * @property-read bool $tags
 * @property-read DateTimeInterface|null $sentTime
 * @property-read Receiver[] $receivers
 * @method static PendingSMS message(string $message)
 * @method static PendingSMS from(string $sender)
 * @method static PendingSMS to(string|string[] $receivers)
 * @method static PendingSMS withOptions(array $options)
 */
final class SMS implements Jsonable
{
  use Attributes;
  use ForwardsCalls;

  public function getSentTimeAttribute(?int $value): ?DateTimeInterface
  {
    return isset($value) ? new DateTime('@' . $value) : null;
  }

  public static function __callStatic($name, $arguments)
  {
    return (new static)
      ->forwardCallTo(new PendingSMS, $name, $arguments);
  }

  public function delete(): bool
  {
    if ($this->exists) {
      KeySMS::post('/messages/destroy/' . $this->id, [$this->primaryKey => $this->id]);
      return true;
    }

    return false;
  }
}
