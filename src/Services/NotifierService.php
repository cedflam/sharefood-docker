<?php

namespace App\Services;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class NotifierService
{
    /**
     * @var NotifierInterface
     */
    private $notifier;

    /**
     * NotifierService constructor.
     * @param NotifierInterface $notifier
     */
    public function __construct(NotifierInterface $notifier)
    {
        return $this->notifier = $notifier;
    }

    /**
     * @param $message
     * @param $article
     */
    public function sendNotification($message, $article)
    {
        //Notification par mail
        $notification = (new Notification(
            'Une personne vous à envoyé un message depuis ShareFood.fr',
            ['email']))->content($message->getMessage()
        );

        // The receiver of the Notification
        $recipient = new Recipient(
            $article->getUser()->getEmail()
        );

        $this->notifier->send($notification, $recipient);
    }
}