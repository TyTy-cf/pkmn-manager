<?php


namespace App\Manager\Locations;


use App\Entity\Locations\LocationArea;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Locations\LocationAreaRepository;
use Doctrine\ORM\EntityManagerInterface;

class LocationAreaManager extends AbstractManager
{
    /**
     * @var LocationAreaRepository $areaRepository
     */
    private LocationAreaRepository $areaRepository;

    /**
     * LocationAreaManager constructor.
     * @param LocationAreaRepository $areaRepository
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        LocationAreaRepository $areaRepository,
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
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
