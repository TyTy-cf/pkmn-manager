<?php

namespace App\Repository\Locations;

use App\Entity\Locations\LocationArea;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LocationArea|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocationArea|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocationArea[]    findAll()
 * @method LocationArea[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationAreaRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocationArea::class);
    }
}
