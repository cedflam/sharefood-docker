<?php

namespace App\Controller;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            //
        ]);
    }
}

/**
 * TODO : Faire un formulaire de contact
 * TODO: Paramétrer l'espace d'administration
 * TODO: Redimenssionner une image automatiquement à l'upload
 */