<?php

namespace App\Repository;

use App\Entity\PasswordForgot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordForgot|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordForgot|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordForgot[]    findAll()
 * @method PasswordForgot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordForgotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordForgot::class);
    }
}
