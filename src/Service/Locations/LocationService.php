<?php


namespace App\Service\Locations;


use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Locations\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;

class LocationService extends AbstractService
{
    /**
     * @var LocationRepository $locationRepo
     */
    private LocationRepository $locationRepo;

    /**
     * LocationService constructor.
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityService
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        LocationRepository $locationRepo,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->locationRepo = $locationRepo;
        parent::__construct($entityService, $apiService, $textService);
    }
}
