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
     * @param $user
     */
    public function sendNotification($message, $article, $user)
    {
        //Je crée une nouvelle notification
        $notification = $this->createNotification($message);
        //Si l'utilisateur connecté est l'auteur de l'article
        if ($article->getMessages()[0]->getUserTarget() === $user){
            // The receiver of the Notification
            $recipient = $article->getMessages()[0]->getUser()->getEmail();
        }else{
            $recipient = $article->getUSer()->getEmail();
        }



        //envoi
        $this->notifier->send($notification, new Recipient($recipient));
    }

    /**
     * Crée une nouevlle notification
     * @param $message
     * @return Notification
     */
    public function createNotification($message)
    {
        return (new Notification('Une personne vous à envoyé un message depuis ShareFood.fr',
            ['email']))->content($message->getMessage()
        );
    }
}