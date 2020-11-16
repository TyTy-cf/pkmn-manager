<?php


namespace App\Manager;


use App\Entity\Pokemon\Pokemon;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;

class PokemonManager
{
    /**
     * @var PokemonRepository
     */
    private $pokemonRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AbilitiesManager
     */
    private $abilitiesManager;

    /**
     * @var TypeManager
     */
    private $typeManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilitiesManager $abilitiesManager
     * @param TypeManager $typeManager
     */
    public function __construct(EntityManagerInterface $entityManager, AbilitiesManager $abilitiesManager, TypeManager $typeManager)
    {
        $this->entityManager = $entityManager;
        $this->pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $this->abilitiesManager = $abilitiesManager;
        $this->typeManager = $typeManager;
    }

    /**
     * Get pokemon based on criterias
     *
     * @param array $array
     * @return Pokemon[]|object[]
     */
    public function findby(array $array)
    {
        return $this->pokemonRepository->findBy($array);
    }

    /**
     * @param $name
     * @return Pokemon|object|null
     */
    public function findByName($name)
    {
        return $this->pokemonRepository->findOneBy(['nameEn' => $name]);
    }

    /**
     * Save a new pokemon from API to the database
     * Create his abilities and type(s) if necessary
     *
     * @param array $apiResponse
     * @param string $pokemonName
     * @return Pokemon
     */
    public function saveNewPokemon(array $apiResponse, string $pokemonName)
    {
        $pokemon = new Pokemon();
        $pokemon->setNameEn(ucfirst($pokemonName));
        $pokemon->setUrlimg($apiResponse['sprites']['other']['dream_world']['front_default']);
        // Add the stats
        foreach ($apiResponse['stats'] as $stat) {
            if ($stat['stat']['name'] == 'hp') {
                $pokemon->setHp($stat['base_stat']);
            } elseif ($stat['stat']['name'] == 'attack') {
                $pokemon->setAtk($stat['base_stat']);
            } elseif ($stat['stat']['name'] == 'defense') {
                $pokemon->setDef($stat['base_stat']);
            } elseif ($stat['stat']['name'] == 'special-attack') {
                $pokemon->setSpa($stat['base_stat']);
            } elseif ($stat['stat']['name'] == 'special-defense') {
                $pokemon->setSpd($stat['base_stat']);
            } elseif ($stat['stat']['name'] == 'speed') {
                $pokemon->setSpe($stat['base_stat']);
            }
        }
        $this->abilitiesManager->saveNewAbilities($apiResponse['abilities'], $pokemon);
        $this->typeManager->saveNewTypes($apiResponse['types'], $pokemon);
        $this->entityManager->persist($pokemon);
        $this->entityManager->flush();
        return $pokemon;
    }
}