<?php


namespace App\Repository\Location;


use App\Entity\Locations\Region;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegionRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

}
