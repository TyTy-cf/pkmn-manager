<?php


namespace App\Repository\Moves;


use App\Entity\Moves\Move;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MoveRepository
 * @package App\Repository\Infos
 */
class MoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Move::class);
    }
}