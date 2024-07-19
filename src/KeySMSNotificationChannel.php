<?php

namespace KeySMS;

use KeySMS\SMS;

class KeySMSNotificationChannel
{
    public function send($notifiable, $notification)
    {
        SMS::to($notifiable)
            ->message($notification->toSMS($notifiable))
            ->send();
    }
}
