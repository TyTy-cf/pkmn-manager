<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiesManager;
use App\Manager\Infos\TypeManager;
use App\Repository\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * @var ApiManager
     */
    private $apiManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilitiesManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AbilitiesManager $abilitiesManager,
        TypeManager $typeManager,
        ApiManager $apiManager
    ) {
        $this->entityManager = $entityManager;
        $this->pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $this->abilitiesManager = $abilitiesManager;
        $this->typeManager = $typeManager;
        $this->apiManager = $apiManager;
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
        //Return french name of Pokémon
        $url = $apiResponse['species']['url'];
        $pokemonNameFr = $this->getInformationFrPokemon('fr', $url);

        $pokemon = new Pokemon();
        $pokemon->setNameFr($pokemonNameFr);
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

    /**
     * @param $lang
     * @param $url
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getInformationFrPokemon($lang, $url)
    {
        $apiResponse = $this->apiManager->getDetailed($url)->toArray();

        foreach ($apiResponse['names'] as $namePokemon) {
            if ($namePokemon['language']['name'] === $lang) {
                $namePokemonFr = $namePokemon['name'];
            }
        }

        return $namePokemonFr;
    }
}