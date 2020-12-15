<?php


namespace App\Repository\Locations;


use App\Entity\Locations\Location;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class LocationRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }
}
