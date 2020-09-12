<?php

namespace App\Services;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DiscussionSnifferService extends AbstractController
{
    /**@var EntityManagerInterface */
    private $manager;
    /**@var MessageRepository */
    private $messageRepository;

    /**
     * Constructeeur
     * DiscussionSnifferService constructor.
     * @param EntityManagerInterface $manager
     * @param MessageRepository $messageRepository
     */
    public function __construct(EntityManagerInterface $manager, MessageRepository $messageRepository)
    {
        $this->manager = $manager;
        $this->messageRepository = $messageRepository;
    }

    /**
     * Permet paramétrer un nouveau message et de le persister
     * @param $article
     * @param $message
     */
    public function setMessageUniqId($article, $message)
    {
        $message->setDiscussion(uniqid())
            ->setArticle($article)
            ->setUser($this->getUser())
            ->setUserTarget($article->getUser());

        $this->manager->persist($message);
    }

    /**
     * Cherche si ce message est adressée à une discussion en rapport avec l'article passé en param
     * @param $article
     * @param $message
     */
    public function discussionSniffer($article, $message)    {

        //Je recherche si une discussion existe
        $discussion = $this->messageRepository->newMessageFindDiscussion($this->getUser(), $article->getUser(), $article);


        if ($article->getUser() !== $this->getUser() && $discussion) {
            //Si je ne suis pas l'auteur de l'article et que la discussion existe
            //Envoi
            $message->setUserTarget($article->getUser())
                ->setUser($this->getUser())
                ->setArticle($article)
                ->setDiscussion($discussion[0]->getDiscussion());

            $this->manager->persist($message);

        } elseif ($article->getUser() === $this->getUser() && $discussion) {
            // Sinon Si je suis l'auteur de l'article et que la discussion existe
            //Réponse
            $message->setUserTarget($this->getUser())
                ->setUser($discussion[0]->getUser())
                ->setArticle($article)
                ->setDiscussion($discussion[0]->getDiscussion());

            $this->manager->persist($message);
        } else {
            //Je crée une nouvelle discussion
            $this->setMessageUniqId($article, $message);
        }
        $this->manager->flush();
    }
}