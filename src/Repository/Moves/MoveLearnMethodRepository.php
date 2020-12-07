<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveLearnMethodRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveLearnMethod::class);
    }

}