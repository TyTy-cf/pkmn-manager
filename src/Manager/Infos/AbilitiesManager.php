<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Abilities;
use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\AbilitiesRepository;
use Doctrine\ORM\EntityManagerInterface;

class AbilitiesManager
{
    /**
     * @var AbilitiesRepository
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
        $this->abilitiesRepository = $this->entityManager->getRepository(Abilities::class);
    }

    /**
     * @param $abilities
     * @param Pokemon $pokemon
     */
    public function saveNewAbilities($abilities, $pokemon)
    {
        // Iterate the types from the json, create the type if not existing or get it
        foreach ($abilities as $ability) {
            $abilityName = $ability['ability']['name'];
            if (($newAbility = $this->abilitiesRepository->findOneBy(['nameEn' => $abilityName])) == null) {

                $urlAbility = $ability['ability']['url'];

                $abilityDetails = $this->apiManager->getAbilitiesDetailed($urlAbility);
                dump($abilityDetails);
                die();


                $newAbility = new Abilities();
                $newAbility->setNameEn(ucfirst($abilityName));

                $this->entityManager->persist($newAbility);
            }
            $pokemon->addAbilities($newAbility);
            $this->entityManager->flush();
        }
    }
}