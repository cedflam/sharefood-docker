<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * Formulaire de contact et fil de discussion
     *
     * @Route("/messages/product/{id}", name="message_product")
     * @param Article $article
     * @param MessageRepository $messageRepository
     * @return Response
     */
    public function showMessageProduct(Article $article, MessageRepository $messageRepository)
    {
        return $this->render('message/thread_product.html.twig',[
            'article' => $article,
            'messages' => $messageRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    public function addMessageProduct()
    {
        //Ajax
    }
}
