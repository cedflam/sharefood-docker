<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Services\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class ArticleController extends AbstractController
{
    /* @var EntityManagerInterface */
    public $manager;

    /**
     * ArticleController constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;

    }

    /**
     * Affiche la liste des produits
     *
     * @Route("/articles", name="articles")
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository)
    {
        return $this->render('article/index.html.twig', [
            "articles" => $articleRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * Permet d'ajouter un nouveau produit
     *
     * @Route("/articles/add", name="article_add")
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function addArticle(Request $request, ImageUploader $imageUploader)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*Je lie l'user au produit*/
            $article->setUser($this->getUser());

            /*****Upload******/
            $imageFile = $form->get('image')->getData();

            if ($imageFile){
                $imageFileName = $imageUploader->upload($imageFile);
                $article->setImage($imageFileName);
            }
            /******************/


            /*Persistance*/
            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash('success', 'Le produit a bien été ajouté.');

            return $this->redirectToRoute('articles');

        }

        return $this->render('article/form_article.html.twig', [
            'form' => $form->createView(),
        ]);


    }

    /**
     * Permet de modifier produit
     *
     * @Route("/articles/{id}/edit", name="article_edit")
     * @param Article $article
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return Response
     */
    public function editArticle(Article $article, Request $request, ImageUploader $imageUploader)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /*****Upload******/
            $imageFile = $form->get('image')->getData();

            if ($imageFile){

                //Suppression de l'ancienne image
                $filename = $article->getImage();
                $fileSystem = new Filesystem();
                $path = $this->getParameter('kernel.project_dir');
                $fileSystem->remove($path . '/public/uploads/images/' . $filename);

                //Enregistrementde la nouvelle
                $imageFileName = $imageUploader->upload($imageFile, $filename);
                $article->setImage($imageFileName);
            }
            /******************/

            /*Persistance*/
            $this->manager->persist($article);
            $this->manager->flush();

            /*Flash*/
            $this->addFlash('success', 'Le produit a bien été modifié.');

            /*Redirection*/
            return $this->redirectToRoute('articles');
        }

        return $this->render('article/form_article.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer produit
     *
     * @Route("/articles/{id}/delete", name="article_delete")
     * @param Article $article
     * @return RedirectResponse
     */
    public function deleteArticle(Article $article)
    {
        //Suppression de l'image
        $filename = $article->getImage();
        $fileSystem = new Filesystem();
        $path = $this->getParameter('kernel.project_dir');
        $fileSystem->remove($path . '/public/uploads/images/' . $filename);

        //flash
        $this->addFlash('success', 'Le produit a bien été supprimé.');

        //Suppression du produit
        $this->manager->remove($article);
        $this->manager->flush();

        return $this->redirectToRoute('articles');
    }
}
