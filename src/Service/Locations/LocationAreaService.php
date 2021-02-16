<?php


namespace App\Service\Locations;


use App\Entity\Locations\LocationArea;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextManager;
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
     * @param ApiService $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        LocationAreaRepository $areaRepository,
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextManager $textManager
    )
    {
        $this->areaRepository = $areaRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return LocationArea|object|null
     */
    public function getLocationAreaBySloug(string $slug) {
        return $this->areaRepository->findOneBySlug($slug);
    }
}
