<?php


namespace App\Manager\Pokemon;


use App\Entity\Infos\Ability;
use App\Entity\Infos\PokemonAbility;
use App\Entity\Infos\Type\Type;
use App\Entity\Locations\Region;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Entity\Stats\StatsEffort;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilityManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Moves\MoveLearnMethodManager;
use App\Manager\Moves\MoveManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\PokemonMovesLearnVersionRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Stats\StatsEffortRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonManager extends AbstractManager
{

    /**
     * @var AbilityManager
     */
    private AbilityManager $abilitiesManager;

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
     * @var PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
     */
    private PokemonMovesLearnVersionRepository $repoMovesLearnPokemon;

    /**
     * @var MoveLearnMethodManager $moveLearnMethodManager
     */
    private MoveLearnMethodManager $moveLearnMethodManager;

    /**
     * @var PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     */
    private PokemonSpeciesVersionManager $pokemonSpeciesVersionManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param AbilityManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param MoveManager $movesManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionGroupManager $versionGroupManager
     * @param StatsEffortRepository $statsEffortRepo
     * @param PokemonRepository $pokemonRepository
     * @param MoveLearnMethodManager $moveLearnMethodManager
     * @param PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     * @param PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        AbilityManager $abilitiesManager,
        TypeManager $typeManager,
        MoveManager $movesManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionGroupManager $versionGroupManager,
        StatsEffortRepository $statsEffortRepo,
        PokemonRepository $pokemonRepository,
        MoveLearnMethodManager $moveLearnMethodManager,
        PokemonSpeciesVersionManager $pokemonSpeciesVersionManager,
        PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
    ) {
        $this->typeManager = $typeManager;
        $this->movesManager = $movesManager;
        $this->pokemonSpeciesVersionManager = $pokemonSpeciesVersionManager;
        $this->repoMovesLearnPokemon = $repoMovesLearnPokemon;
        $this->moveLearnMethodManager = $moveLearnMethodManager;
        $this->statsEffortRepo = $statsEffortRepo;
        $this->abilitiesManager = $abilitiesManager;
        $this->versionGroupManager = $versionGroupManager;
        $this->pokemonRepository = $pokemonRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @return Pokemon[]
     */
    public function getAllPokemonByLanguage(Language $language)
    {
        return $this->pokemonRepository->getAllPokemonByLanguage($language);
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
     * @param string $slug
     * @return Pokemon|null
     * @throws NonUniqueResultException
     */
    public function getPokemonPofileBySlug(string $slug): ?Pokemon
    {
        return $this->pokemonRepository->getPokemonPofileBySlug($slug);
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
     * @return array
     */
    public function getAllPokemonNameForLanguage(Language $language): array
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
     * @param Pokedex $pokedex
     * @return int|mixed|string
     */
    public function getPokemonsByPokedex(Pokedex $pokedex)
    {
        return $this->pokemonRepository->getPokemonsByPokedex($pokedex);
    }

    /**
     * @param PokemonSpecies $pokemonSpecies
     * @return mixed
     */
    public function getPokemonSpriteByPokemonSpecies(PokemonSpecies $pokemonSpecies)
    {
        return $this->pokemonRepository->getPokemonSpriteByPokemonSpecies($pokemonSpecies);
    }

    /**
     * @param Pokemon $pokemon
     * @return array
     */
    public function generateArrayByVersionForPokemon(Pokemon $pokemon) {
        // Initialise the array of VersionGroup
        $arrayMoves = [];
        $language = $pokemon->getLanguage();
        $arrayMoves['version_groups'] = array();
        $allMoveLearnMethod = $this->moveLearnMethodManager->getAllMoveLearnMethodByLanguage($language);
        $versionsGroups = $this->versionGroupManager->getArrayVersionGroup($language);
        if (sizeof($versionsGroups) > 0) {
            foreach($versionsGroups as $versionGroup) {
                /** @var VersionGroup $versionGroup */
                $arrayMovesLearn = [];
                // Fetch moves for pokemons by versions
                foreach($allMoveLearnMethod as $moveLearnMethod) {
                    /** @var MoveLearnMethod $moveLearnMethod */
                    if ($moveLearnMethod->getSlug() === $language->getCode().MoveLearnMethod::SLUG_MACHINE) {
                        $moves = $this->repoMovesLearnPokemon->getMovesLearnMachineBy($pokemon, $moveLearnMethod, $versionGroup);
                    } else {
                        $moves = $this->repoMovesLearnPokemon->getMovesLearnBy($pokemon, $moveLearnMethod, $versionGroup);
                    }
                    if (!empty($moves)) {
                        $arrayMovesLearn[$moveLearnMethod->getName()] = $moves;
                    }
                }
                if (count($arrayMovesLearn) > 0) {
                    array_push($arrayMoves['version_groups'], [
                        'id' => $versionGroup->getSlug(),
                        'name' => $versionGroup->getName(),
                    ]);
                    $arrayMoves['moves_infos'][$versionGroup->getSlug()] = [
                        'moves' => $arrayMovesLearn
                    ];
                }
            }
        }
        return $arrayMoves;
    }

    /**
     * @param Pokemon $pokemon
     * @return array
     */
    public function generateArrayMovesMachinesForPokemon(Pokemon $pokemon) {
        // Initialise the array of VersionGroup
        $arrayMoves = [];
        $language = $pokemon->getLanguage();
        $arrayMoves['version_groups'] = array();
        $moveLearnMethodMachine = $this->moveLearnMethodManager->getMoveLearnMethodBySlug(
            $this->textManager->generateSlugFromClassWithLanguage(
                $language, MoveLearnMethod::class, 'machine'
            )
        );
        $versionsGroups = $this->versionGroupManager->getVersionGroupByLanguage($language);
        if (sizeof($versionsGroups) > 0) {
            foreach($versionsGroups as $versionGroup) {
                $arrayMovesLearn = [];
                $moves = $this->repoMovesLearnPokemon->getMovesLearnMachineBy($pokemon, $moveLearnMethodMachine, $versionGroup);
                if (!empty($moves)) {
                    $arrayMovesLearn[$moveLearnMethodMachine->getName()] = $moves;
                }
                if (count($arrayMovesLearn) > 0) {
                    $arrayMoves['moves_infos'][$versionGroup->getSlug()] = $arrayMovesLearn;
                }
            }
        }
        return $arrayMoves;
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

        if (($pokemon = $this->getPokemonBySlug($slug)) === null && sizeof($urlDetailed['stats']) > 0)
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
                ->setLanguage($language)
                ->setIsDefault($urlDetailed['is_default']);
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
                if ($ability !== null) {
                    $pokemonAbility = (new PokemonAbility())
                        ->setPokemon($pokemon)
                        ->setAbility($ability)
                        ->setHidden($abilityDetailed['is_hidden'])
                    ;
                    $this->entityManager->persist($pokemonAbility);
                }
            }

            $this->entityManager->persist($pokemon);
        }
        $this->entityManager->flush();
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