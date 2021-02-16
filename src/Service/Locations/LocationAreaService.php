<?php


namespace App\Service\Locations;


use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Locations\LocationAreaRepository;
use Doctrine\ORM\EntityManagerInterface;

class LocationAreaService extends AbstractService
{
    /**
     * @var LocationAreaRepository $areaRepository
     */
    private LocationAreaRepository $areaRepository;

    /**
     * LocationAreaService constructor.
     * @param LocationAreaRepository $areaRepository
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct(
        LocationAreaRepository $areaRepository,
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->areaRepository = $areaRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }
}
