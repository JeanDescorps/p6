<?php

namespace App\Repository;

use App\Entity\PasswordForgot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PasswordForgot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordForgot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordForgot[]    findAll()
 * @method PasswordForgot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordForgotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PasswordForgot::class);
    }

    // /**
    //  * @return PasswordForgot[] Returns an array of PasswordForgot objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PasswordForgot
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
