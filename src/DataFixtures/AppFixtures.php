<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Message;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;



    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $products = ['pommes', 'conserves', 'carottes', 'petits pois', 'pain de mie', 'jambon', 'fromage', 'lait', 'eau', 'sucre'];


        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setRoles(['ROLE_USER'])
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setPhone($faker->e164PhoneNumber)
                ;


            for ($j = 0; $j < 10; $j++) {
                $article = new Article();
                $article->setProductName($faker->randomElement($products))
                        ->setDescription($faker->sentence(20))
                        ->setExpiratedAt($faker->dateTimeBetween('-30days', '+15days'))
                        ->setImage('productDefault.jpg')
                        ->setCreatedAt(new \DateTime())
                        ->setLocation($faker->city)
                        ->setAvailable(true)
                        ->setUser($user);

                for ($k = 0; $k < 1; $k++){
                    $message = new Message();
                    $message->setSubject($faker->sentence(3))
                            ->setMessage($faker->sentence(15))
                            ->setCreatedAt($faker->dateTimeBetween('-10days', 'now'))
                            ->setUser($user)
                            ->setArticle($article)
                    ;
                    $manager->persist($message);
                }

                $manager->persist($article);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}
