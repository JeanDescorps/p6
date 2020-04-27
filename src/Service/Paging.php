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

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function getData()
    {
        // Offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // Get elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy($this->criteria, $this->order, $this->limit, $offset);

        // Return
        return $data;
    }

    public function getPages()
    {
        // Total page
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findBy($this->criteria));
        $pages = intval(ceil($total / $this->limit));
        return $pages;
    }

    /**
     * Get the value of manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set the value of manager
     *
     * @return  self
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }
}
