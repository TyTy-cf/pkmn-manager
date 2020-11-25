<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiesManager;
use App\Manager\Infos\TypeManager;
use App\Manager\Users\LanguageManager;
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
    private EntityManagerInterface $entityManager;

    /**
     * @var AbilitiesManager
     */
    private AbilitiesManager $abilitiesManager;

    /**
     * @var TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager
     */
    private LanguageManager $languageManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilitiesManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AbilitiesManager $abilitiesManager,
        TypeManager $typeManager,
        LanguageManager $languageManager,
        ApiManager $apiManager
    ) {
        $this->entityManager = $entityManager;
        $this->pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $this->abilitiesManager = $abilitiesManager;
        $this->typeManager = $typeManager;
        $this->languageManager = $languageManager;
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
     * @param string $lang
     * @param array $apiResponse
     * @param string $pokemonName
     * @return Pokemon
     * @throws TransportExceptionInterface
     */
    public function saveNewPokemon(string $lang, array $apiResponse, string $pokemonName)
    {
        $language = $this->languageManager->createLanguage($lang);

        //Return foreign name of Pokémon
        $url = $apiResponse['species']['url'];
        $pokemonName = $this->getPokemonInformationsOnLanguage($lang, $url);

        //Create new Pokemon
        $pokemon = new Pokemon();
        $pokemon->setName(ucfirst($pokemonName));
//        $pokemon->setNameFr($pokemonNameFr);
//        $pokemon->setNameEn(ucfirst($pokemonName));
        $pokemon->setUrlimg($apiResponse['sprites']['other']['dream_world']['front_default']);
        $pokemon->setLanguage($language);

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

        $this->abilitiesManager->saveNewAbilities($language, $lang, $apiResponse['abilities'], $pokemon);
        $this->typeManager->saveNewTypes($language, $lang, $apiResponse['types'], $pokemon);
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
    public function getPokemonInformationsOnLanguage(string $lang, string $url): string
    {
        $apiResponse = $this->apiManager->getDetailed($url)->toArray();
        $namePokemonFr = null;

        foreach ($apiResponse['names'] as $namePokemon) {
            if ($namePokemon['language']['name'] === $lang) {
                $pokemonName = $namePokemon['name'];
            }
        }

        return $pokemonName;
    }
}