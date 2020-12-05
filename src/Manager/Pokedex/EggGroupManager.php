<?php


namespace App\Manager\Pokedex;


use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Moves\DamageClassRepository;
use App\Repository\Pokedex\EggGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class EggGroupManager extends AbstractManager
{
    /**
     * @var EggGroupRepository
     */
    private EggGroupRepository $eggGroupRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param EggGroupRepository $eggGroupRepository
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EggGroupRepository $eggGroupRepository,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->eggGroupRepository = $eggGroupRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }
}