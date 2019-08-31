<?php


namespace Nickmel\SMSTo;

use Illuminate\Notifications\Notification;
use Nickmel\SMSTo\Exception\CouldNotSendNotification;
use Nickmel\SMSTo\Messages\SMSToMessage;

class SMSToChannel
{
    protected $smsTo;

    protected $from;

    public function __construct(SMSTo $smsTo, $from = null)
    {
        $this->smsTo = $smsTo;

        if ($from)
        {
            $this->from = $from;
        }
    }

    public function send($notifiable, Notification $notification)
    {
        if(! $to = $notifiable->routeNotificationFor('smsto', $notification))
        {
            return;
        }

        $message = $notification->toSMSTo($notifiable);

        if (is_string($message))
        {
            $message = new SMSToMessage($message);
        }

        return $this->smsTo->sendSingle(
            [
                'to' => $to,
                'message' => $message->content
            ]
        );
    }
}