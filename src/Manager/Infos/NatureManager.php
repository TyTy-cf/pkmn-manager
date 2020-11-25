<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Nature;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\NatureRepository;
use Doctrine\ORM\EntityManagerInterface;

class NatureManager
{
    /**
     * @var NatureRepository
     */
    private $abilitiesRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->abilitiesRepository = $this->entityManager->getRepository(Nature::class);
    }


}