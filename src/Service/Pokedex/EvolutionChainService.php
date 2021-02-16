<?php


namespace App\Service\Pokedex;


use App\Entity\Infos\Gender;
use App\Entity\Pokedex\EvolutionChain;
use App\Entity\Pokedex\EvolutionChainLink;
use App\Entity\Pokedex\EvolutionDetail;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Repository\Infos\Type\TypeRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Infos\Type\TypeService;
use App\Service\Items\ItemService;
use App\Service\Locations\LocationService;
use App\Service\Moves\MoveService;
use App\Service\Pokemon\PokemonService;
use App\Service\Pokemon\PokemonSpeciesService;
use App\Service\TextService;
use App\Repository\Infos\GenderRepository;
use App\Repository\Pokedex\EvolutionChainLinkRepository;
use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionChainService extends AbstractService
{
    /**
     * @var EvolutionChainRepository $evolutionChainRepository
     */
    private EvolutionChainRepository $evolutionChainRepository;

    /**
     * @var EvolutionChainLinkRepository $evolutionChainLinkRepository
     */
    private EvolutionChainLinkRepository $evolutionChainLinkRepository;

    /**
     * @var GenderRepository $genderRepository
     */
    private GenderRepository $genderRepository;

    /**
     * @var EvolutionTriggerService $evolutionTriggerManager
     */
    private EvolutionTriggerService $evolutionTriggerManager;

    /**
     * @var PokemonSpeciesService $pokemonSpeciesManager
     */
    private PokemonSpeciesService $pokemonSpeciesManager;

    /**
     * @var LocationService $locationManager
     */
    private LocationService $locationManager;

    /**
     * @var ItemService $itemManager
     */
    private ItemService $itemManager;

    /**
     * @var MoveService $moveManager
     */
    private MoveService $moveManager;

    /**
     * @var TypeService $typeManager
     */
    private TypeService $typeManager;

    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * @var TypeRepository $typeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * EvolutionChainService constructor.
     * @param EvolutionChainLinkRepository $evolutionChainLinkRepository
     * @param EvolutionChainRepository $evolutionChainRepository
     * @param TypeRepository $typeRepository
     * @param EvolutionTriggerService $evolutionTriggerService
     * @param PokemonSpeciesService $pokemonSpeciesManager
     * @param EntityManagerInterface $entityManager
     * @param GenderRepository $genderRepository
     * @param LocationService $locationManager
     * @param PokemonService $pokemonManager
     * @param ItemService $itemManager
     * @param MoveService $moveManager
     * @param TypeService $typeManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     */
    public function __construct
    (
        EvolutionChainLinkRepository $evolutionChainLinkRepository,
        EvolutionChainRepository $evolutionChainRepository,
        TypeRepository $typeRepository,
        EvolutionTriggerService $evolutionTriggerService,
        PokemonSpeciesService $pokemonSpeciesManager,
        EntityManagerInterface $entityManager,
        GenderRepository $genderRepository,
        LocationService $locationManager,
        PokemonService $pokemonManager,
        ItemService $itemManager,
        MoveService $moveManager,
        TypeService $typeManager,
        ApiService $apiManager,
        TextService $textManager
    )
    {
        $this->evolutionChainRepository = $evolutionChainRepository;
        $this->evolutionChainLinkRepository = $evolutionChainLinkRepository;
        $this->typeRepository = $typeRepository;
        $this->itemManager = $itemManager;
        $this->moveManager = $moveManager;
        $this->typeManager = $typeManager;
        $this->pokemonManager = $pokemonManager;
        $this->locationManager = $locationManager;
        $this->genderRepository = $genderRepository;
        $this->pokemonSpeciesManager = $pokemonSpeciesManager;
        $this->evolutionTriggerManager = $evolutionTriggerService;
        parent::__construct($entityManager, $apiManager, $textManager);
    }


    /**
     * @param string $slug
     * @return EvolutionChain|null
     */
    public function getEvolutionChainBySlug(string $slug)
    {
        return $this->evolutionChainRepository->findOneBySlug($slug);
    }

    /**
     * @param Pokemon $pokemon
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function generateEvolutionChainFromPokemon(Pokemon $pokemon)
    {
        $evolutionChain = $this->evolutionChainRepository->getEvolutionChainByPokemon($pokemon);
        $arrayEvolutionChain = [];
        if ($evolutionChain !== null) {
            $evolutionChainLinks = $this->evolutionChainLinkRepository->getEvolutionChainLinkByEvolutionChain($evolutionChain);
            foreach ($evolutionChainLinks as $evolutionChainLink) {
                /** @var EvolutionChainLink $evolutionChainLink */
                $this->setEvolutionChainArray($arrayEvolutionChain, $evolutionChainLink, $evolutionChain);
            }
        }
        return $arrayEvolutionChain;
    }

    /**
     * @param array $arrayEvolutionChain
     * @param EvolutionChainLink $evolutionChainLink
     * @param EvolutionChain $evolutionChain
     */
    private function setEvolutionChainArray(
        array &$arrayEvolutionChain,
        EvolutionChainLink $evolutionChainLink,
        EvolutionChain $evolutionChain
    ) {
        if (!isset($arrayEvolutionChain[$evolutionChainLink->getEvolutionOrder()])) {
            $arrayEvolutionChain[$evolutionChainLink->getEvolutionOrder()] = [];
        }
        array_push($arrayEvolutionChain[$evolutionChainLink->getEvolutionOrder()],
            [
                'pokemon' => $this->pokemonManager->getPokemonSpriteByPokemonSpecies(
                    $evolutionChainLink->getCurrentPokemonSpecies()
                ),
                'evolution_detail' => $evolutionChainLink->getEvolutionDetail(),
                'is_baby' => $evolutionChainLink->isBaby(),
                'baby_trigger_item' => $evolutionChain->getBabyItemTrigger(),
            ]
        );
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL evolution type
        $urlPokemonSpecies = $this->apiManager->apiConnect($apiResponse['url'])->toArray();
        $idApi = $this->apiManager->getIdFromUrl($apiResponse['url']);
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, EvolutionChain::class, $idApi
        );

        // Check and create if necessary the evolution chain
        // Can be empty, unecessary to even create the evolution_chain starting
        if (sizeof($urlPokemonSpecies['chain']['evolves_to']) > 0 && $this->getEvolutionChainBySlug($slug) === null) {
            // if a baby item trigger is required
            $babyItemTrigger = null;
            if ($urlPokemonSpecies['baby_trigger_item'] !== null) {
                $babyItemTrigger = $this->itemManager->getItemBySlug(
                    $language->getCode() . '/item-' . $urlPokemonSpecies['baby_trigger_item']['name']
                );
            }

            // Create the initial evolution chain
            $evolutionChain = (new EvolutionChain())
                ->setIdApi($idApi)
                ->setBabyItemTrigger($babyItemTrigger)
                ->setSlug($slug)
            ;
            $this->entityManager->persist($evolutionChain);

            // Create the EvolutionChainLink - there at least one evolution exist
            $evolutionLevel = 1;
            $arrayChainEvolveTo = $urlPokemonSpecies['chain'];
            // Create the starting element of the chain, the one that will be set to the evolution chain
            $startingSpecies = $this->pokemonSpeciesManager->getSimplePokemonSpeciesBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language, PokemonSpecies::class, $arrayChainEvolveTo['species']['name']
                )
            );
            // create the 1st evolution link : the starting pokemon species
            $evolutionChainLink = (new EvolutionChainLink())
                ->setIsBaby($arrayChainEvolveTo['is_baby'])
                ->setCurrentPokemonSpecies($startingSpecies)
                ->setEvolutionOrder($evolutionLevel)
                ->setEvolutionDetail(null)
                ->setEvolutionChain($evolutionChain)
            ;
            $this->entityManager->persist($evolutionChainLink);
            // all pokemon species in the evolution chain have the same evolution chain
            $startingSpecies->setEvolutionChain($evolutionChain);
            // Create the rest of the link
            $index = 0;
            $evolutionLevel++;
            $arrayChainEvolveTo = $arrayChainEvolveTo['evolves_to'];
            while (isset($arrayChainEvolveTo[$index])) {
                // set the new evolution chain link to the evolution chain
                $evolutionChain->addEvolutionChainLinks($this->createEvolutionChainLinkFrom(
                    $arrayChainEvolveTo[$index], $language, $evolutionChain, $evolutionLevel
                ));
                $index++;
                // We need to change stage
                if (!isset($arrayChainEvolveTo[$index])) {
                    // reset the index
                    $index = 0;
                    // increase the evolution level
                    $evolutionLevel++;
                    // set to the next array evolves_to
                    $arrayChainEvolveTo = $arrayChainEvolveTo[$index]['evolves_to'];
                }
            }
            $this->entityManager->flush();
        }

    }

    /**
     * @param $urlEvolutionChain
     * @param Language $language
     * @param EvolutionChain $evolutionChain
     * @param int $evolutionLevel
     * @return null
     * @throws NonUniqueResultException
     */
    private function createEvolutionChainLinkFrom(
        $urlEvolutionChain,
        Language $language,
        EvolutionChain $evolutionChain,
        int $evolutionLevel
    ) {
        // Fetch the pokemon-species from the species api json
        $pokemonSpecies = $this->pokemonSpeciesManager->getSimplePokemonSpeciesBySlug(
            $this->textManager->generateSlugFromClassWithLanguage(
                $language, PokemonSpecies::class, $urlEvolutionChain['species']['name']
            )
        );
        // evolution_details can be empty, we need to handle it
        $evolutionDetail = null;
        if (isset($urlEvolutionChain['evolution_details'][0])) {
            $evolutionDetail = $this->getEvolutionDetailFromJson($urlEvolutionChain['evolution_details'][0], $language);
        }
        // create the evolution chain link
        // order is the order of the evolution chain link in the evolution chain
        $newEvolutionChainLink = (new EvolutionChainLink())
            ->setIsBaby($urlEvolutionChain['is_baby'])
            ->setEvolutionDetail($evolutionDetail)
            ->setCurrentPokemonSpecies($pokemonSpecies)
            ->setEvolutionOrder($evolutionLevel)
            ->setEvolutionChain($evolutionChain);
        ;
        $this->entityManager->persist($newEvolutionChainLink);
        // Set the pokemon species to the starting evolution chain
        $pokemonSpecies->setEvolutionChain($evolutionChain);
        return $newEvolutionChainLink;
    }

    /**
     * @param $urlEvolutionChainDetailed
     * @param Language $language
     * @return EvolutionDetail
     * @throws NonUniqueResultException
     */
    private function getEvolutionDetailFromJson($urlEvolutionChainDetailed, Language $language): EvolutionDetail {
        // the pokemon require to be a specific gender
        $gender = null;
        if ($urlEvolutionChainDetailed['gender'] !== null) {
            $gender = $this->genderRepository->findOneBySlug(
                $language->getCode().'/'.Gender::$relationMap[$urlEvolutionChainDetailed['gender']]
            );
        }
        // the pokemon require an item held
        $heldItem = null;
        if ($urlEvolutionChainDetailed['held_item'] !== null) {
            $heldItem = $this->itemManager->getItemBySlug(
                $language->getCode() . '/item-' . $urlEvolutionChainDetailed['held_item']['name']
            );
        }
        //the pokemon require an item used - usually pair with an evolution trigger "used item"
        $item = null;
        if ($urlEvolutionChainDetailed['item'] !== null) {
            $item = $this->itemManager->getItemBySlug(
                $language->getCode().'/item-'.$urlEvolutionChainDetailed['item']['name']
            );
        }
        // the pokemon require a specific move
        $knownMove = null;
        if ($urlEvolutionChainDetailed['known_move'] !== null) {
            $knownMove = $this->moveManager->getSimpleMoveBySlug(
                $language->getCode().'/move-'.$urlEvolutionChainDetailed['known_move']['name']
            );
        }
        // the pokemon require a type from a specific move
        $knownMoveType = null;
        if ($urlEvolutionChainDetailed['known_move_type'] !== null) {
            $knownMoveType = $this->typeRepository->findOneBySlug(
                $language->getCode().'/type-'.$urlEvolutionChainDetailed['known_move_type']['name']
            );
        }
        // the pokemon require to be in a certain location
        $location = null;
        if ($urlEvolutionChainDetailed['location'] !== null) {
            $location = $this->locationManager->getLocationBySlug(
                $language->getCode().'/location-'.$urlEvolutionChainDetailed['location']['name']
            );
        }
        // the pokemon require a specific species in team
        $partySpecies = null;
        if ($urlEvolutionChainDetailed['party_species'] !== null) {
            $partySpecies = $this->pokemonSpeciesManager->getPokemonSpeciesBySlug(
                $language->getCode() . '/pokemon-species-' . $urlEvolutionChainDetailed['party_species']['name']
            );
        }
        // the pokemon require a pokemon of specific type in team
        $partyType = null;
        if ($urlEvolutionChainDetailed['party_type'] !== null) {
            $partyType = $this->typeRepository->findOneBySlug(
                $language->getCode() . '/type-' . $urlEvolutionChainDetailed['party_type']['name']
            );
        }
        // the evolution trigger that trigger the evolution
        $evolutionTrigger = null;
        if ($urlEvolutionChainDetailed['trigger'] !== null) {
            $evolutionTrigger = $this->evolutionTriggerManager->getEvolutionTriggerBySlug(
                $language->getCode() . '/evolution-trigger-' . $urlEvolutionChainDetailed['trigger']['name']
            );
        }
        // the pokemon require to be trade with a specific species
        $tradeSpecies = null;
        if ($urlEvolutionChainDetailed['trade_species'] !== null) {
            $tradeSpecies = $this->pokemonSpeciesManager->getPokemonSpeciesBySlug(
                $language->getCode() . '/pokemon-species-' . $urlEvolutionChainDetailed['trade_species']['name']
            );
        }

        return (new EvolutionDetail())
            ->setGender($gender)
            ->setHeldItem($heldItem)
            ->setUsedItem($item)
            ->setKnownMove($knownMove)
            ->setKnownMoveType($knownMoveType)
            ->setLocation($location)
            ->setMinHappiness($urlEvolutionChainDetailed['min_happiness'])
            ->setMinAffection($urlEvolutionChainDetailed['min_affection'])
            ->setMinBeauty($urlEvolutionChainDetailed['min_beauty'])
            ->setMinLevel($urlEvolutionChainDetailed['min_level'])
            ->setNeedsOverworldRain($urlEvolutionChainDetailed['needs_overworld_rain'])
            ->setPokemonSpecies($partySpecies)
            ->setPartyType($partyType)
            ->setRelativePhysicalStats($urlEvolutionChainDetailed['relative_physical_stats'])
            ->setTimeOfDay($urlEvolutionChainDetailed['time_of_day'])
            ->setEvolutionTrigger($evolutionTrigger)
            ->setTurnUpsideDown($urlEvolutionChainDetailed['turn_upside_down'])
            ->setTradeSpecies($tradeSpecies)
        ;
    }
}
