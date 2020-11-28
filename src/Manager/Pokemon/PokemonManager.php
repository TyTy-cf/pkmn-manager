<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiesManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
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
     * @var MoveManager
     */
    private MoveManager $movesManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilitiesManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param LanguageManager $languageManager
     * @param MoveManager $movesManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        AbilitiesManager $abilitiesManager,
        TypeManager $typeManager,
        LanguageManager $languageManager,
        MoveManager $movesManager,
        ApiManager $apiManager
    ) {
        $this->entityManager = $entityManager;
        $this->pokemonRepository = $this->entityManager->getRepository(Pokemon::class);
        $this->abilitiesManager = $abilitiesManager;
        $this->typeManager = $typeManager;
        $this->languageManager = $languageManager;
        $this->movesManager = $movesManager;
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
        return $this->pokemonRepository->findOneBy(['name' => $name]);
    }

    /**
     * Save a new pokemon from API to the database
     * Create his abilities and type(s) if necessary
     *
     * @param string $lang
     * @param array $apiResponse
     * @return void
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function saveNewPokemon(string $lang, array $apiResponse)
    {
        $slug = 'pokemon-' . $apiResponse['name'];

        if (($newPokemon = $this->pokemonRepository->getPokemonByLanguageAndSlug($lang, $slug)) == null && $apiResponse['stats'] )
        {
            // Return foreign name of Pokémon
            $url = $apiResponse['species']['url'];
            $pokemonName = $this->apiManager->getNameBasedOnLanguage($lang, $url);
            $language = $this->languageManager->createLanguage($lang);

            // Create new Pokemon
            $pokemon = new Pokemon();
            $pokemon->setName(ucfirst($pokemonName));
            $pokemon->setSlug($slug);
            $pokemon->setUrlIcon($apiResponse['sprites']['versions']['generation-viii']['icons']['front_default']);
            $pokemon->setUrlSpriteImg($apiResponse['sprites']['other']['official-artwork']['front_default']);
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

            // Set the Abilities
            foreach($apiResponse['abilities'] as $abilityDetailed)
            {
                $ability = $this->abilitiesManager->getAbilitiesByLanguageAndSlug($language, 'ability-' . $abilityDetailed['ability']['name']);
                $pokemon->addAbilities($ability);
            }

            // Set the Type
            foreach($apiResponse['types'] as $typesDetailed)
            {
                $type = $this->typeManager->getTypeByLanguageAndSlug($language, 'type-' . $typesDetailed['type']['name']);
                $pokemon->addType($type);
            }

            $this->entityManager->persist($pokemon);
            $this->entityManager->flush();

        }
    }
}