<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use App\Services\DiscussionSnifferService;
use App\Services\NotifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * Permet d'envoyer un message en rapport avec un produit
     *
     * @Route("/messages/product/{id}", name="message_product")
     * @param Article $article
     * @param Request $request
     * @param NotifierService $notification
     * @param DiscussionSnifferService $sniffer
     * @return Response
     */
    public function sendMessageProduct(Article $article, Request $request, NotifierService $notification, DiscussionSnifferService $sniffer)
    {
        //Création du message et du formulaire
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        //Logique à la validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            //Appel du service chargé de rechercher ou de créer une discussion
            $sniffer->discussionSniffer($article, $message);

            //Appel du service de notification
            /*TODO: A décommenter pour activer le service de notifications*/
            //$notification->sendNotification($message, $article);
            //Message flash
            $this->addFlash('success', "Message envoyé à " . $article->getUser()->getFirstName());
            //Redirection
            return $this->redirectToRoute('articles');
        }
        //Vue
        return $this->render('message/_form_send_message_product.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * Permet de visualiser les discussions d'un User connecté
     *
     * @Route("/discussions/show", name="discussion_show")
     * @param ArticleRepository $articleRepository
     * @param MessageRepository $messageRepository
     * @return Response
     */
    public function showDiscussions(ArticleRepository $articleRepository, MessageRepository $messageRepository)
    {

        return $this->render('message/discussions.html.twig', [
            'articles' => $articleRepository->findAll(),
            'discussions' => $messageRepository->findByDiscussion()
        ]);
    }

    /**
     * Permet de visualiser les messages reçus
     *
     * @Route("/messages/show/{id}", name="message_show")
     * @param Article $article
     * @return Response
     */
    public function showMessages(Article $article)
    {
        return $this->render('message/show_messages.html.twig', [
            'article' => $article
        ]);
    }


}
