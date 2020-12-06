<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AbstractRepository extends ServiceEntityRepository
{
    /**
     * AbstractRepository constructor.
     * @param ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry, string $entityClass = '')
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param string $slug
     * @return object|null
     */
    public function findOneBySlug(string $slug)
    {
        return $this->findOneBy([
            'slug' => $slug
        ]);
    }
}