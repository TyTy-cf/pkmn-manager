<?php


namespace App\Repository\Moves;

use App\Entity\Moves\DamageClass;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class DamageClassRepository
 * @package App\Repository\Moves
 */
class DamageClassRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DamageClass::class);
    }
}