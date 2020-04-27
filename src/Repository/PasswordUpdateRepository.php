<?php

namespace App\Repository;

use App\Entity\PasswordUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PasswordUpdate|null find($id, $lockMode = null, $lockVersion = null)
 * @method PasswordUpdate|null findOneBy(array $criteria, array $orderBy = null)
 * @method PasswordUpdate[]    findAll()
 * @method PasswordUpdate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordUpdate::class);
    }
}
