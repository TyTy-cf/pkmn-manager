<?php


namespace App\Manager\Pokemon;


use App\Entity\Infos\Ability;
use App\Entity\Infos\Type\Type;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Stats\StatsEffort;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiyManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Moves\MoveManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonManager extends AbstractManager
{

    /**
     * @var AbilitiyManager
     */
    private AbilitiyManager $abilitiesManager;

    /**
     * @var TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var MoveManager
     */
    private MoveManager $movesManager;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * @var PokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilitiyManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param MoveManager $movesManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionGroupManager $versionGroupManager
     * @param PokemonRepository $pokemonRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        AbilitiyManager $abilitiesManager,
        TypeManager $typeManager,
        MoveManager $movesManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionGroupManager $versionGroupManager,
        PokemonRepository $pokemonRepository
    ) {
        $this->typeManager = $typeManager;
        $this->movesManager = $movesManager;
        $this->abilitiesManager = $abilitiesManager;
        $this->versionGroupManager = $versionGroupManager;
        $this->pokemonRepository = $pokemonRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
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
     * @param string $name
     * @param string $languageCode
     * @return Pokemon|null
     * @throws NonUniqueResultException
     */
    public function getPokemonByNameAndLanguageCode(string $name, string $languageCode): ?Pokemon
    {
        return $this->pokemonRepository->getPokemonByNameAndLanguageCode($name, $languageCode);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Pokemon|null
     * @throws NonUniqueResultException
     */
    public function getPokemonByLanguageAndSlug(Language $language, string $slug): ?Pokemon
    {
        return $this->pokemonRepository->getPokemonByLanguageAndSlug($language, $slug);
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
     * @param Language $language
     * @param $apiResponse
     * @return void
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $slug = $this->textManager->generateSlugFromClass(Pokemon::class, $apiResponse['name']);
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();

        if ($this->getPokemonByLanguageAndSlug($language, $slug) === null && sizeof($urlDetailed['stats']) > 0)
        {
            $apiResponse = $this->apiManager->getPokemonFromName($urlDetailed['name']);
            $pokemonName = $this->apiManager->getNameBasedOnLanguage($language->getCode(), $urlDetailed['species']['url']);

            // Create new Pokemon
            $pokemon = new Pokemon();
            $pokemon->setName(ucfirst($pokemonName));
            $pokemon->setSlug($slug);
            $pokemon->setWeight($urlDetailed['weight']);
            $pokemon->setHeight($urlDetailed['height']);
            $pokemon->setUrlIcon($urlDetailed['sprites']['versions']['generation-viii']['icons']['front_default']);
            $pokemon->setUrlSpriteImg($urlDetailed['sprites']['other']['official-artwork']['front_default']);
            $pokemon->setLanguage($language);

            // Add the stats
            $statsEffort = new StatsEffort();
            foreach ($urlDetailed['stats'] as $stat) {
                if ($stat['stat']['name'] == 'hp') {
                    $pokemon->setHp($stat['base_stat']);
                    $statsEffort->setHp($stat['effort']);
                } elseif ($stat['stat']['name'] == 'attack') {
                    $pokemon->setAtk($stat['base_stat']);
                    $statsEffort->setAtk($stat['effort']);
                } elseif ($stat['stat']['name'] == 'defense') {
                    $pokemon->setDef($stat['base_stat']);
                    $statsEffort->setDef($stat['effort']);
                } elseif ($stat['stat']['name'] == 'special-attack') {
                    $pokemon->setSpa($stat['base_stat']);
                    $statsEffort->setSpa($stat['effort']);
                } elseif ($stat['stat']['name'] == 'special-defense') {
                    $pokemon->setSpd($stat['base_stat']);
                    $statsEffort->setSpd($stat['effort']);
                } elseif ($stat['stat']['name'] == 'speed') {
                    $pokemon->setSpe($stat['base_stat']);
                    $statsEffort->setSpe($stat['effort']);
                }
            }
            // Persist the StatsEffort
            $this->entityManager->persist($statsEffort);
            $pokemon->setStatsEffort($statsEffort);

            // Set the Ability
            foreach($urlDetailed['abilities'] as $abilityDetailed)
            {
                $slugAbility = $this->textManager->generateSlugFromClass(Ability::class, $abilityDetailed['ability']['name']);
                $ability = $this->abilitiesManager->getAbilitiesByLanguageAndSlug($language, $slugAbility);
                $pokemon->addAbilities($ability);
            }

            // Set the Type
            foreach($urlDetailed['types'] as $typesDetailed)
            {
                $slugType = $this->textManager->generateSlugFromClass(Type::class, $typesDetailed['type']['name']);
                $type = $this->typeManager->getTypeByLanguageAndSlug($language, $slugType);
                $pokemon->addType($type);
            }

            $this->entityManager->persist($pokemon);
            $this->entityManager->flush();
        }
    }
}