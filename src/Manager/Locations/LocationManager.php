<?php


namespace App\Manager\Locations;


use App\Entity\Locations\Location;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Locations\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class LocationManager extends AbstractManager
{
    /**
     * @var LocationRepository $locationRepo
     */
    private LocationRepository $locationRepo;

    /**
     * LocationManager constructor.
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct
    (
        LocationRepository $locationRepo,
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->locationRepo = $locationRepo;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Location|object|null
     */
    public function getLocationBySlug(string $slug) {
        return $this->locationRepo->findOneBySlug($slug);
    }
}
