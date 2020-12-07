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
use App\Repository\Stats\StatsEffortRepository;
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
     * @var StatsEffortRepository $statsEffortRepo
     */
    private StatsEffortRepository $statsEffortRepo;

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
     * @param StatsEffortRepository $statsEffortRepo
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
        StatsEffortRepository $statsEffortRepo,
        PokemonRepository $pokemonRepository
    ) {
        $this->typeManager = $typeManager;
        $this->movesManager = $movesManager;
        $this->statsEffortRepo = $statsEffortRepo;
        $this->abilitiesManager = $abilitiesManager;
        $this->versionGroupManager = $versionGroupManager;
        $this->pokemonRepository = $pokemonRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * Get pokemon based on criteria
     *
     * @param array $array
     * @return Pokemon[]|object[]
     */
    public function findBy(array $array)
    {
        return $this->pokemonRepository->findBy($array);
    }

    /**
     * @param string $slug
     * @return Pokemon|null
     */
    public function getPokemonBySlug(string $slug): ?Pokemon
    {
        return $this->pokemonRepository->findOneBySlug($slug);
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
     * @param string $name
     * @param Language $language
     * @return Pokemon|null
     */
    public function getPokemonByNameAndLanguage(string $name, Language $language)
    {
        return $this->pokemonRepository->findOneBy([
           'name' => $name,
            'language' => $language
        ]);
    }

    /**
     * @param Language $language
     * @return Pokemon|object|null
     */
    public function getAllPokemonNameForLanguage(Language $language)
    {
        return $this->pokemonRepository->getAllPokemonNameForLanguage($language);
    }

    /**
     * @param Language $language
     * @param int $offset
     * @param int $limit
     * @return array|int|string
     */
    public function getPokemonOffsetLimitByLanguage(Language $language, int $offset, int $limit)
    {
        return $this->pokemonRepository->getPokemonOffsetLimitByLanguage($language, $offset, $limit);
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
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Pokemon::class, $apiResponse['name']
        );
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();

        if ($this->getPokemonBySlug($slug) === null && sizeof($urlDetailed['stats']) > 0)
        {
            $pokemonName = $this->apiManager->getNameBasedOnLanguage(
                $language->getCode(),
                $urlDetailed['species']['url']
            );

            // Create new Pokemon
            $pokemon = (new Pokemon())
                ->setIdApi($urlDetailed['id'])
                ->setNameApi($urlDetailed['name'])
                ->setName(ucfirst($pokemonName))
                ->setSlug($slug)
                ->setWeight($urlDetailed['weight'])
                ->setHeight($urlDetailed['height'])
                ->setUrlIcon($urlDetailed['sprites']['versions']['generation-viii']['icons']['front_default'])
                ->setUrlSpriteImg($urlDetailed['sprites']['other']['official-artwork']['front_default'])
                ->setLanguage($language)
            ;

            // Add the stats
            $arrayStatsEffort = array();
            foreach ($urlDetailed['stats'] as $stat) {
                if ($stat['stat']['name'] == 'hp') {
                    $pokemon->setHp($stat['base_stat']);
                    $arrayStatsEffort['hp'] = $stat['effort'];
                }
                if ($stat['stat']['name'] == 'attack') {
                    $pokemon->setAtk($stat['base_stat']);
                    $arrayStatsEffort['atk'] = $stat['effort'];
                }
                if ($stat['stat']['name'] == 'defense') {
                    $pokemon->setDef($stat['base_stat']);
                    $arrayStatsEffort['def'] = $stat['effort'];
                }
                if ($stat['stat']['name'] == 'special-attack') {
                    $pokemon->setSpa($stat['base_stat']);
                    $arrayStatsEffort['spa'] = $stat['effort'];
                }
                if ($stat['stat']['name'] == 'special-defense') {
                    $pokemon->setSpd($stat['base_stat']);
                    $arrayStatsEffort['spd'] = $stat['effort'];
                }
                if ($stat['stat']['name'] == 'speed') {
                    $pokemon->setSpe($stat['base_stat']);
                    $arrayStatsEffort['spe'] = $stat['effort'];
                }
            }

            $pokemon->setStatsEffort($this->getStatsEffortFromArray($arrayStatsEffort));

            // Set the Ability
            foreach($urlDetailed['abilities'] as $abilityDetailed)
            {
                $ability = $this->abilitiesManager->getAbilitiesBySlug(
                    $this->textManager->generateSlugFromClassWithLanguage(
                        $language,
                        Ability::class,
                        $abilityDetailed['ability']['name']
                    )
                );
                $pokemon->addAbilities($ability);
            }

            // Set the Type
            foreach($urlDetailed['types'] as $typesDetailed)
            {
                $type = $this->typeManager->getTypeBySlug(
                    $this->textManager->generateSlugFromClassWithLanguage(
                        $language,
                        Type::class,
                        $typesDetailed['type']['name']
                    )
                );
                $pokemon->addType($type);
            }

            $this->entityManager->persist($pokemon);
            $this->entityManager->flush();
        }
    }

    /**
     * @param array $arrayStatsEffort
     * @return StatsEffort
     * @throws NonUniqueResultException
     */
    public function getStatsEffortFromArray(array $arrayStatsEffort)
    {
        // Add the stats
        if (($statsEffort = $this->statsEffortRepo->getStatsEffortByStats($arrayStatsEffort)) === null)
        {
            $statsEffort = (new StatsEffort())
                ->setHp($arrayStatsEffort['hp'])
                ->setAtk($arrayStatsEffort['atk'])
                ->setDef($arrayStatsEffort['def'])
                ->setSpa($arrayStatsEffort['spa'])
                ->setSpd($arrayStatsEffort['spd'])
                ->setSpe($arrayStatsEffort['spe'])
            ;
            $this->entityManager->persist($statsEffort);
        }
        return $statsEffort;
    }
}