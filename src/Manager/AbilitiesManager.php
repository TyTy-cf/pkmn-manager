<?php


namespace App\Manager;


use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Repository\AbilitiesRepository;
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
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
                $newAbility = new Abilities();
                $newAbility->setNameEn(ucfirst($abilityName));
                $this->entityManager->persist($newAbility);
            }
            $pokemon->addAbilities($newAbility);
            $this->entityManager->flush();
        }
    }
}