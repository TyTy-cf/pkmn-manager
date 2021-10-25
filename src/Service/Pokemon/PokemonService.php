<?php


namespace App\Service\Pokemon;


use App\Entity\Infos\Ability;
use App\Entity\Infos\PokemonAbility;
use App\Entity\Infos\Type\Type;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Stats\StatsEffort;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Repository\Infos\PokemonAbilityRepository;
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


/**
 * Class PokemonService
 * @package App\Service\Pokemon
 *
 * @property VersionGroupService $versionGroupService
 * @property AbilityRepository $abilitiesRepo
 * @property TypeRepository $typeRepo
 * @property PokemonRepository $pokemonRepository
 * @property StatsEffortRepository $statsEffortRepo
 * @property PokemonMovesLearnVersionRepository $movesLearnPokemonRepo
 * @property MoveLearnMethodRepository $moveLearnMethodRepo
 * @property PokemonSpritesVersionRepository $spritesVersionRepository
 * @property VersionGroupRepository $versionGroupRepo
 * @property MoveMachineRepository $moveMachineRepository
 * @property PokemonAbilityRepository $pokemonAbilityRepository
 */
class PokemonService extends AbstractService
{

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
     * @param PokemonAbilityRepository $pokemonAbilityRepository
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
        VersionGroupRepository $versionGroupRepo,
        PokemonAbilityRepository $pokemonAbilityRepository
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
        $this->pokemonAbilityRepository = $pokemonAbilityRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @return Pokemon[]
     */
    public function getAllPokemonByLanguage(Language $language): array
    {
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
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        $speciesUrl = $urlDetailed['species']['url'];
        $isDefault = $urlDetailed['is_default'];
        $codeLanguage = $language->getCode();
        $pokemonName = $this->apiService->getNameBasedOnLanguage($codeLanguage, $speciesUrl);

        if (!$isDefault) {
            $pokemonName = $this->apiService->getNameBasedOnLanguage($codeLanguage, $urlDetailed['forms'][0]['url']);
            if ($pokemonName === null) {
                $pokemonName = $this->apiService->getNameBasedOnLanguage('en', $urlDetailed['forms'][0]['url']);
            }
        }
        $slug = $this->textService->slugify($pokemonName);

        $urlDetailedStats = $urlDetailed['stats'];
        if (sizeof($urlDetailedStats) > 0) {
            if (null === $pokemon = $this->getPokemonBySlug($slug)) {
                $pokemon = (new Pokemon())
                    ->setIdApi($urlDetailed['id'])
                    ->setNameApi($urlDetailed['name'])
                    ->setWeight($urlDetailed['weight'])
                    ->setHeight($urlDetailed['height'])
                    ->setName(ucfirst($pokemonName))
                    ->setIsDefault($isDefault)
                    ->setLanguage($language)
                    ->setSlug($slug)
                ;
            }

            // Add the stats
            $this->setStatsToPokemonFromJsonArray($urlDetailedStats, $pokemon);

            // Set the Type
            foreach($urlDetailed['types'] as $typesDetailed) {
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
                if (null !== $ability && null === $this->pokemonAbilityRepository->findOneBy(['pokemon' => $pokemon, 'ability' => $ability])) {
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
    }

    /**
     * @param array $urlDetailedStats
     * @param Pokemon $pokemon
     * @throws NonUniqueResultException
     */
    private function setStatsToPokemonFromJsonArray(array $urlDetailedStats, Pokemon $pokemon)
    {
        $arrayStatsEffort = array();
        foreach ($urlDetailedStats as $stat) {
            $statValue = $stat['stat']['name'];
            if ($statValue === 'hp') {
                $pokemon->setHp($stat['base_stat']);
                $arrayStatsEffort['hp'] = $stat['effort'];
            } else if ($statValue === 'attack') {
                $pokemon->setAtk($stat['base_stat']);
                $arrayStatsEffort['atk'] = $stat['effort'];
            } else if ($statValue === 'defense') {
                $pokemon->setDef($stat['base_stat']);
                $arrayStatsEffort['def'] = $stat['effort'];
            } else if ($statValue === 'special-attack') {
                $pokemon->setSpa($stat['base_stat']);
                $arrayStatsEffort['spa'] = $stat['effort'];
            } else if ($statValue === 'special-defense') {
                $pokemon->setSpd($stat['base_stat']);
                $arrayStatsEffort['spd'] = $stat['effort'];
            } else if ($statValue === 'speed') {
                $pokemon->setSpe($stat['base_stat']);
                $arrayStatsEffort['spe'] = $stat['effort'];
            }
        }
        $pokemon->setStatsEffort($this->getStatsEffortFromArray($arrayStatsEffort));
    }

    /**
     * @param array $arrayStatsEffort
     * @return StatsEffort
     * @throws NonUniqueResultException
     */
    public function getStatsEffortFromArray(array $arrayStatsEffort): StatsEffort
    {
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
