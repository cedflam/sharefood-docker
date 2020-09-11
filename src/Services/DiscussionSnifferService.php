<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscussionSnifferService extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * Constructeeur
     * DiscussionSnifferService constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Permet paramétrer un nouveau message et de le persister
     * @param $article
     * @param $message
     */
    public function setMessageUniqId($article, $message)
    {
        $message->setArticle($article)
            ->setUser($this->getUser())
            ->setUserTarget($article->getUser())
            ->setDiscussion(uniqid());
        $this->manager->persist($message);
    }

    /**
     * Cherche si ce message est adressée à une discussion en rapport avec l'article passé en param
     * @param $article
     * @param $message
     */
    public function discussionSniffer($article, $message)
    {

        if (count($article->getMessages()) !== 0) {

            foreach ($article->getMessages() as $messageExist) {
                /**
                 * Si l'annonce est égale à l'annonce passée en param ET
                 * Que je suis l'auteur du message ET
                 * Que je ne suis pas l'auteur de l'annonce ET
                 * Que l'auteur de l'article est déjà ma cible
                 */
                if (
                    $messageExist->getArticle() === $article &&
                    $messageExist->getUser() === $this->getUser() && $messageExist->getUser() !== $article->getUser() &&
                    $article->getUser() === $messageExist->getUserTarget()
                ) {
                    $discussion = $messageExist->getDiscussion();
                    $message->setArticle($article)
                        ->setUser($this->getUser())
                        ->setUserTarget($article->getUser())
                        ->setDiscussion($discussion);
                    $this->manager->persist($message);
                }else{
                   $this->setMessageUniqId($article, $message);
                }

            }

        } else {
           $this->setMessageUniqId($article, $message);
        }
        $this->manager->flush();
    }



}