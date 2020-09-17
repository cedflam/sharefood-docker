<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productName', TextType::class, [
                'label' => "Nom du produit"
            ])
            ->add('location', TextType::class, [
                'label' => 'Ville ou se trouve le produit'
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'label' => "Description"
                ]
            ])
            ->add('expiratedAt', DateType::class, [
                'label' => "Date de péremption du produit",
                'widget' => "single_text"
            ])
            ->add('image', FileType::class, [
                'label' => "Ajouter/Modifier une image du produit",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => "Format invalide, merci de télécharger des images au format jpg, jpeg, gif ou png",

                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
