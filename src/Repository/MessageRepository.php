<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */

    public function findByDiscussion()
    {
        /* $parameters = [
            'article' => $article
         ];*/

        return $this->createQueryBuilder('m')

            //->setParameters($parameters)
            ->groupBy('m.discussion')
            ->getQuery()
            ->getResult();
    }


    public function newMessageFindDiscussion($user, $userTarget, $article)
    {
        $parameters = [
            'user' => $user,
            'userTarget' => $userTarget,
            'article' => $article,
        ];

        return $this->createQueryBuilder('m')
            ->where('m.article = :article')
            ->andWhere('m.user = :user OR m.userTarget = :userTarget')
            ->setParameters($parameters)
            ->getQuery()
            ->getResult();
    }

    public function findUserTargetAndUser($user, $userTarget, $article){
        $paramters = [
            'userId' => $user,
            'userTargetId' => $userTarget,
            'article' => $article
        ];

        return $this->createQueryBuilder('m')
                    ->where("m.article = :article")
                    ->andWhere('m.user = :userId')
                    ->andWhere('m.userTarget = :userTargetId')
                    ->setParameters($paramters)
                    ->groupBy('m.message')
                    ->getQuery()
                    ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
