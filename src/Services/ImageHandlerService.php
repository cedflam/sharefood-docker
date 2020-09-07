<?php

namespace App\Services;

use App\Entity\Article;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageHandlerService
{
    /**
     * @param $path
     * @param $form
     * @param $article
     * @throws Exception
     */
    public function uploadImage($path, $form, $article)
    {
        if ($image = $form['image']->getData()) {
            $filename = $this->createName($image);
            $image->move($path, $filename);
            $article->setImage($filename);
        }
        /*TODO: Mettre en place un système de redimenssionement de l'image*/
    }

    /**
     * @param $image
     * @return string
     * @throws Exception
     */
    private function createName($image)
    {
        return $filename = bin2hex(random_bytes(6)) . '.' . $image->guessExtension();
    }
}