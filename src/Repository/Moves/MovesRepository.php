<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Moves;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MovesRepository
 * @package App\Repository\Infos
 */
class MovesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moves::class);
    }
}