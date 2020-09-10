<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class MessageController extends AbstractController
{
    /**
     * Formulaire de contact et fil de discussion
     *
     * @Route("/messages/product/{id}", name="message_product")
     * @param Article $article
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function sendMessageProduct(Article $article, Request $request, ArticleRepository $articleRepository)
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

            $this->addFlash('success', "Message envoyé !");

            return $this->redirectToRoute('articles');
        }

        return $this->render('message/_form_send_message_product.html.twig', [
                'form' => $form->createView(),
                'article' => $article
        ]);




    }


}
