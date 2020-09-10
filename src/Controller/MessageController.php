<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ArticleRepository;
use App\Services\NotifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\AdminRecipient;
use Symfony\Component\Notifier\Recipient\Recipient;
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
     * @return Response
     */
    public function sendMessageProduct(Article $article, Request $request, NotifierService $notification)
    {
        //Création du message et du formulaire
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        //Logique à la validation du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $message->setArticle($article)
                    ->setUser($this->getUser())
            ;
            $manager->persist($message);
            $manager->flush();
            //Appel du service de notification
            $notification->sendNotification($message, $article);
            //Message flash
            $this->addFlash('success', "Message envoyé à ".$article->getUser()->getFirstName());
            //Redirection
            return $this->redirectToRoute('articles');
        }
        //Vue
        return $this->render('message/_form_send_message_product.html.twig', [
                'form' => $form->createView(),
                'article' => $article
        ]);
    }



}
