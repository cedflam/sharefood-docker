<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ArticleRepository;
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
     * Formulaire de contact et fil de discussion
     *
     * @Route("/messages/product/{id}", name="message_product")
     * @param Article $article
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @param NotifierInterface $notifier
     * @return Response
     */
    public function sendMessageProduct(Article $article, Request $request, ArticleRepository $articleRepository, NotifierInterface $notifier)
    {

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $message->setArticle($article)
                    ->setUser($this->getUser())
            ;
            $manager->persist($message);
            $manager->flush();

            //Notification par mail
            $notification = (new Notification(
                'Une personne vous à envoyé un message depuis ShareFood.fr',
                ['email']))->content($message->getMessage()
            );

            // The receiver of the Notification
            $recipient = new Recipient(
                $article->getUser()->getEmail()
            );

            $notifier->send($notification, $recipient);

            $this->addFlash('success', "Message envoyé à ".$article->getUser()->getFirstName());

            return $this->redirectToRoute('articles');
        }

        return $this->render('message/_form_send_message_product.html.twig', [
                'form' => $form->createView(),
                'article' => $article
        ]);
    }



}
