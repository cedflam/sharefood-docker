<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
       return $this->render('bundles/EasyAdminBundle/welcome.html.twig', [

       ]);
    }

    /**
     * @return Dashboard
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Sharefood');
    }

    /**
     * @return iterable
     */
    public function configureMenuItems(): iterable
    {

        // links to the 'index' action of the Category CRUD controller
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),
            MenuItem::linkToCrud('Articles', 'fa fa-tags', Article::class),
            MenuItem::linkToCrud('Users', 'fa fa-users', User::class),

        ];
        //yield MenuItem::section('Articles');
        //yield MenuItem::linkToCrud('Articles', 'fa fa-drumstick-bite', Article::class);
        // yield MenuItem::linkToCrud('The Label', 'icon class', EntityClass::class);
    }


}
