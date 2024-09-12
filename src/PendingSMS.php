<?php

namespace KeySMS;

use DateTimeInterface;
use JsonSerializable;

use KeySMS\Exception\Exception;
use KeySMS\Facades\KeySMS;

use Illuminate\Contracts\Support\Jsonable;

final class PendingSMS implements JsonSerializable, Jsonable
{
    public static ?string $defaultSender = null;

    protected $response = null;
    protected ?string $message = null;
    protected ?string $sender = null;
    protected array $receivers = [];
    protected array $options = [];
    protected bool $dryRun = false;
    protected bool $sent = false;
    protected ?DateTimeInterface $sendAt = null;

    /**
     * Set the message of the SMS
     *
     * @param string $message 
     * @return PendingSMS 
     */
    public function message(string $message): PendingSMS
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the sender of the SMS
     *
     * @param string $sender 
     * @return PendingSMS 
     */
    public function from(string $sender): PendingSMS
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Set the receiver(s) of the SMS
     *
     * @param string|string[]|Contact|Contact[] $receivers 
     * @return PendingSMS 
     */
    public function to($receivers): PendingSMS
    {
        $this->receivers = is_array($receivers) ? $receivers : [$receivers];
        return $this;
    }

    public function withOptions(array $options): PendingSMS
    {
        $this->options = $options;
        return $this;
    }

    public function dryRun(bool $dryRun = true): PendingSMS
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * Send the SMS
     * 
     * @return SMS 
     * @throws Exception 
     */
    public function send(): SMS
    {
        if (!$this->sent) {
            $this->response = KeySMS::post('/messages', $this->jsonSerialize())['message'];
            $this->sent = true;
        }

        return new SMS($this->response, $this);
    }

    /**
     * Send the SMS at a later time
     * 
     * @param DateTimeInterface $dateTime
     * @return SMS
     * @throws Exception
     */
    public function later(DateTimeInterface $dateTime): SMS
    {
        $this->sendAt = $dateTime;
        return $this->send();
    }

    public function jsonSerialize(): mixed
    {
        $date = null;
        $time = null;

        $sender = $this->sender;

        if (!$sender) {
            $sender = static::$defaultSender;
        }

        if ($this->sendAt !== null) {
            $date = $this->sendAt->format('Y-m-d');
            $time = $this->sendAt->format('H:i');
        }

        return array_filter([
            'dryRun' => $this->dryRun,
            'sender' => $sender,
            'receivers' => array_values(
                array_map(
                    fn($receiver) => (string) $receiver,
                    $this->receivers
                )
            ),
            'message' => $this->message,
            'options' => $this->options,
            'date' => $date,
            'time' => $time,
        ]);
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function __destruct()
    {
        $this->send();
    }
}
