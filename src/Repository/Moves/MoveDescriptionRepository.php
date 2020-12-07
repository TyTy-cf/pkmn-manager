<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveDescription;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveDescriptionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveDescription::class);
    }

}