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
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextManager;
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
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

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
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextManager $textManager
     * @param TypeRepository $typeRepo
     * @param AbilityRepository $abilitiesRepo
     * @param VersionGroupService $versionGroupManager
     * @param MoveLearnMethodRepository $moveLearnMethodRepo
     * @param PokemonSpritesVersionRepository $spritesVersionRepository
     * @param StatsEffortRepository $statsEffortRepo
     * @param PokemonRepository $pokemonRepository
     * @param PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextManager $textManager,
        VersionGroupService $versionGroupManager,
        TypeRepository $typeRepo,
        AbilityRepository $abilitiesRepo,
        MoveLearnMethodRepository $moveLearnMethodRepo,
        PokemonSpritesVersionRepository $spritesVersionRepository,
        StatsEffortRepository $statsEffortRepo,
        PokemonRepository $pokemonRepository,
        PokemonMovesLearnVersionRepository $repoMovesLearnPokemon
    ) {
        $this->versionGroupManager = $versionGroupManager;

        $this->typeRepo = $typeRepo;
        $this->abilitiesRepo = $abilitiesRepo;
        $this->statsEffortRepo = $statsEffortRepo;
        $this->pokemonRepository = $pokemonRepository;
        $this->moveLearnMethodRepo = $moveLearnMethodRepo;
        $this->movesLearnPokemonRepo = $repoMovesLearnPokemon;
        $this->spritesVersionRepository = $spritesVersionRepository;

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
    public function getPokemonProfileBySlug(string $slug): ?Pokemon
    {
        return $this->pokemonRepository->getPokemonProfileBySlug($slug);
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
        $language = $pokemon->getLanguage();
        $arrayMoves['version_groups'] = array();
        $allMoveLearnMethod = $this->moveLearnMethodRepo->getAllMoveLearnMethodByLanguage($language);
        $versionsGroups = $this->versionGroupManager->getArrayVersionGroup($language);
        if (sizeof($versionsGroups) > 0) {
            foreach($versionsGroups as $versionGroup) {
                /** @var VersionGroup $versionGroup */
                $arrayMovesLearn = [];
                // Fetch moves for pokemons by versions
                foreach($allMoveLearnMethod as $moveLearnMethod) {
                    /** @var MoveLearnMethod $moveLearnMethod */
                    if ($moveLearnMethod->getSlug() === $language->getCode().MoveLearnMethod::SLUG_MACHINE) {
                        $moves = $this->movesLearnPokemonRepo->getMovesLearnMachineBy($pokemon, $moveLearnMethod, $versionGroup);
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
     * @param Pokemon $pokemon
     * @return mixed
     */
    public function getSpritesArrayByPokemon(Pokemon $pokemon): array
    {
        $versionsGroups = $this->versionGroupManager->getVersionGroupByLanguage($pokemon->getLanguage(), 'DESC');
        $arraySprites = [];
        if (sizeof($versionsGroups) > 0) {
            foreach($versionsGroups as $versionGroup) {
               $sprites = $this->spritesVersionRepository->getSpritesByVersionGroupIdAndPokemon($versionGroup, $pokemon);
               if (count($sprites) > 0) {
                   $arraySprites[$versionGroup->getName()] = $sprites;
               }
            }
        }
        return $arraySprites;
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
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();

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
                $type = $this->typeRepo->findOneBySlug(
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
                $ability = $this->abilitiesRepo->findOneBySlug(
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