<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function sendMail(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $subject = $request->request->get('contact_form')['subject'];
            $message = $request->request->get('contact_form')['message'];

            $email = new Email();
            $email->from($this->getUser()->getUsername())
                ->to('cedflam@gmail.com')
                ->subject($subject)
                ->text($message);

            $mailer->send($email);

            $this->addFlash('success', "Le message a bien été envoyé à l'équipe de ShareFood.fr");

            return $this->redirectToRoute('contact');

        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
