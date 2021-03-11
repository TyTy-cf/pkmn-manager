<?php


namespace App\Service\Pokemon;


use App\Entity\Infos\Ability;
use App\Entity\Infos\PokemonAbility;
use App\Entity\Infos\Type\Type;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Stats\StatsEffort;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Versions\VersionGroupRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\Type\TypeRepository;
use App\Repository\Moves\MoveLearnMethodRepository;
use App\Repository\Moves\PokemonMovesLearnVersionRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Pokemon\PokemonSpritesVersionRepository;
use App\Repository\Stats\StatsEffortRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonService extends AbstractService
{

    /**
     * @var VersionGroupService $versionGroupService
     */
    private VersionGroupService $versionGroupService;

    /**
     * @var AbilityRepository $abilitiesRepo
     */
    private AbilityRepository $abilitiesRepo;

    /**
     * @var TypeRepository $typeRepo
     */
    private TypeRepository $typeRepo;

    /**
     * @var PokemonRepository $pokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * @var StatsEffortRepository $statsEffortRepo
     */
    private StatsEffortRepository $statsEffortRepo;

    /**
     * @var PokemonMovesLearnVersionRepository $movesLearnPokemonRepo
     */
    private PokemonMovesLearnVersionRepository $movesLearnPokemonRepo;

    /**
     * @var MoveLearnMethodRepository $moveLearnMethodRepo
     */
    private MoveLearnMethodRepository $moveLearnMethodRepo;

    /**
     * @var PokemonSpritesVersionRepository $spritesVersionRepository
     */
    private PokemonSpritesVersionRepository $spritesVersionRepository;

    /**
     * @var VersionGroupRepository $versionGroupRepo
     */
    private VersionGroupRepository $versionGroupRepo;

    /**
     * @var MoveMachineRepository $moveMachineRepository
     */
    private MoveMachineRepository $moveMachineRepository;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param VersionGroupService $versionGroupService
     * @param TypeRepository $typeRepo
     * @param AbilityRepository $abilitiesRepo
     * @param MoveLearnMethodRepository $moveLearnMethodRepo
     * @param PokemonSpritesVersionRepository $spritesVersionRepository
     * @param StatsEffortRepository $statsEffortRepo
     * @param PokemonRepository $pokemonRepository
     * @param MoveMachineRepository $moveMachineRepository
     * @param PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
     * @param VersionGroupRepository $versionGroupRepo
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        VersionGroupService $versionGroupService,
        TypeRepository $typeRepo,
        AbilityRepository $abilitiesRepo,
        MoveLearnMethodRepository $moveLearnMethodRepo,
        PokemonSpritesVersionRepository $spritesVersionRepository,
        StatsEffortRepository $statsEffortRepo,
        PokemonRepository $pokemonRepository,
        MoveMachineRepository $moveMachineRepository,
        PokemonMovesLearnVersionRepository $repoMovesLearnPokemon,
        VersionGroupRepository $versionGroupRepo
    ) {
        $this->moveMachineRepository = $moveMachineRepository;
        $this->versionGroupRepo = $versionGroupRepo;
        $this->versionGroupService = $versionGroupService;
        $this->typeRepo = $typeRepo;
        $this->abilitiesRepo = $abilitiesRepo;
        $this->statsEffortRepo = $statsEffortRepo;
        $this->pokemonRepository = $pokemonRepository;
        $this->moveLearnMethodRepo = $moveLearnMethodRepo;
        $this->movesLearnPokemonRepo = $repoMovesLearnPokemon;
        $this->spritesVersionRepository = $spritesVersionRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @return Pokemon[]
     */
    public function getAllPokemonByLanguage(Language $language) {
        return $this->pokemonRepository->getAllPokemonByLanguage($language);
    }

    /**
     * @param string $slug
     * @return Pokemon|null
     */
    public function getPokemonBySlug(string $slug): ?Pokemon {
        return $this->pokemonRepository->findOneBySlug($slug);
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
    public function getArrayMovesByVersionForPokemon(Pokemon $pokemon): array
    {
        // Initialise the array of VersionGroup
        $language = $pokemon->getLanguage();
        $arrayMoves['version_groups'] = array();
        $moveLearnMethodByPokemon = $this->moveLearnMethodRepo->getMoveLearnMethodByLanguageAndPokemon($language, $pokemon);
        $versionsGroupByPokemon = $this->versionGroupRepo->getVersionGroupByLanguageAndPokemon($language, $pokemon, 'DESC');
        if (sizeof($versionsGroupByPokemon) > 0) {
            foreach($versionsGroupByPokemon as $versionGroup) {
                /** @var VersionGroup $versionGroup */
                $arrayMovesLearn = [];
                // Fetch moves for pokemons by versions
                foreach($moveLearnMethodByPokemon as $moveLearnMethod) {
                    /** @var MoveLearnMethod $moveLearnMethod */
                    if ($moveLearnMethod->getCodeMethod() === MoveLearnMethod::CODE_MACHINE) {
                        $moves = $this->moveMachineRepository->getMovesMachineBy($pokemon, $moveLearnMethod, $versionGroup);
                    } else {
                        $moves = $this->movesLearnPokemonRepo->getMovesLearnBy($pokemon, $moveLearnMethod, $versionGroup);
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
                        'moves' => $arrayMovesLearn,
                    ];
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
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, Pokemon::class, $apiResponse['name']
        );
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();

        if (null === $pokemon = $this->getPokemonBySlug($slug) && sizeof($urlDetailed['stats']) > 0) {
            $pokemonName = $this->apiService->getNameBasedOnLanguage(
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
                if ($stat['stat']['name'] === 'hp') {
                    $pokemon->setHp($stat['base_stat']);
                    $arrayStatsEffort['hp'] = $stat['effort'];
                }
                if ($stat['stat']['name'] === 'attack') {
                    $pokemon->setAtk($stat['base_stat']);
                    $arrayStatsEffort['atk'] = $stat['effort'];
                }
                if ($stat['stat']['name'] === 'defense') {
                    $pokemon->setDef($stat['base_stat']);
                    $arrayStatsEffort['def'] = $stat['effort'];
                }
                if ($stat['stat']['name'] === 'special-attack') {
                    $pokemon->setSpa($stat['base_stat']);
                    $arrayStatsEffort['spa'] = $stat['effort'];
                }
                if ($stat['stat']['name'] === 'special-defense') {
                    $pokemon->setSpd($stat['base_stat']);
                    $arrayStatsEffort['spd'] = $stat['effort'];
                }
                if ($stat['stat']['name'] === 'speed') {
                    $pokemon->setSpe($stat['base_stat']);
                    $arrayStatsEffort['spe'] = $stat['effort'];
                }
            }

            $pokemon->setStatsEffort($this->getStatsEffortFromArray($arrayStatsEffort));

            // Set the Type
            foreach($urlDetailed['types'] as $typesDetailed)
            {
                $type = $this->typeRepo->findOneBySlug(
                    $this->textService->generateSlugFromClassWithLanguage(
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
                $ability = $this->abilitiesRepo->findOneBySlug(
                    $this->textService->generateSlugFromClassWithLanguage(
                        $language,
                        Ability::class,
                        $abilityDetailed['ability']['name']
                    )
                );
                if (null !== $ability) {
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
    public function getStatsEffortFromArray(array $arrayStatsEffort): StatsEffort
    {
        // Add the stats
        if (null === $statsEffort = $this->statsEffortRepo->getStatsEffortByStats($arrayStatsEffort))
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
