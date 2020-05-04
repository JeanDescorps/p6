<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Paging
{
    private $entityClass;
    private $limit;
    private $criteria = [];
    private $order = ['id' => 'DESC'];
    private $currentPage = 1;
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass): Paging
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit($limit): Paging
    {
        $this->limit = $limit;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage): Paging
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function setCriteria($criteria): Paging
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getOrder(): array
    {
        return $this->order;
    }

    public function setOrder($order): Paging
    {
        $this->order = $order;

        return $this;
    }

    public function getData(): array
    {
        $offset = $this->currentPage * $this->limit - $this->limit;
        $repo = $this->manager->getRepository($this->entityClass);

        return $repo->findBy($this->criteria, $this->order, $this->limit, $offset);
    }

    public function getPages(): int
    {
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findBy($this->criteria));

        return (int)ceil($total / $this->limit);
    }

    public function getManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    public function setManager($manager): Paging
    {
        $this->manager = $manager;

        return $this;
    }
}
