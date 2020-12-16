<?php


namespace App\Manager\Pokedex;


use App\Entity\Infos\Gender;
use App\Entity\Pokedex\EvolutionChain;
use App\Entity\Pokedex\EvolutionChainLink;
use App\Entity\Pokedex\EvolutionDetail;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Items\ItemManager;
use App\Manager\Locations\LocationManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Pokemon\PokemonSpeciesManager;
use App\Manager\TextManager;
use App\Repository\Infos\GenderRepository;
use App\Repository\Pokedex\EvolutionChainLinkRepository;
use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionChainManager extends AbstractManager
{
    /**
     * @var EvolutionChainRepository $evolutionChainRepository
     */
    private EvolutionChainRepository $evolutionChainRepository;

    /**
     * @var EvolutionChainLinkRepository $evolutionChainChainRepository
     */
    private EvolutionChainLinkRepository $evolutionChainChainRepository;

    /**
     * @var GenderRepository $genderRepository
     */
    private GenderRepository $genderRepository;

    /**
     * @var EvolutionTriggerManager $evolutionTriggerManager
     */
    private EvolutionTriggerManager $evolutionTriggerManager;

    /**
     * @var PokemonSpeciesManager $pokemonSpeciesManager
     */
    private PokemonSpeciesManager $pokemonSpeciesManager;

    /**
     * @var LocationManager $locationManager
     */
    private LocationManager $locationManager;

    /**
     * @var ItemManager $itemManager
     */
    private ItemManager $itemManager;

    /**
     * @var MoveManager $moveManager
     */
    private MoveManager $moveManager;

    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * EvolutionChainManager constructor.
     * @param EvolutionChainRepository $evolutionChainRepository
     * @param EvolutionChainLinkRepository $evolutionChainChainRepository
     * @param GenderRepository $genderRepository
     * @param EvolutionTriggerManager $evolutionTriggerManager
     * @param PokemonSpeciesManager $pokemonSpeciesManager
     * @param LocationManager $locationManager
     * @param ItemManager $itemManager
     * @param MoveManager $moveManager
     * @param TypeManager $typeManager
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct
    (
        EvolutionChainLinkRepository $evolutionChainChainRepository,
        EvolutionChainRepository $evolutionChainRepository,
        EvolutionTriggerManager $evolutionTriggerManager,
        PokemonSpeciesManager $pokemonSpeciesManager,
        EntityManagerInterface $entityManager,
        GenderRepository $genderRepository,
        LocationManager $locationManager,
        ItemManager $itemManager,
        MoveManager $moveManager,
        TypeManager $typeManager,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->evolutionChainRepository = $evolutionChainRepository;
        $this->evolutionChainChainRepository = $evolutionChainChainRepository;
        $this->itemManager = $itemManager;
        $this->moveManager = $moveManager;
        $this->typeManager = $typeManager;
        $this->locationManager = $locationManager;
        $this->genderRepository = $genderRepository;
        $this->pokemonSpeciesManager = $pokemonSpeciesManager;
        $this->evolutionTriggerManager = $evolutionTriggerManager;
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
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL evolution type
        $urlPokemonSpecies = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
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
            ;
            $this->entityManager->persist($evolutionChainLink);
            // the 1st evolution chain link is set to the evolution chain
            $evolutionChain->setEvolutionChainLink($evolutionChainLink);
            // all pokemon species in the evolution chain have the same evolution chain
            $startingSpecies->setEvolutionChain($evolutionChain);
            // Create the rest of the link
            $index = 0;
            $evolutionLevel++;
            $arrayChainEvolveTo = $arrayChainEvolveTo['evolves_to'];
            while (isset($arrayChainEvolveTo[$index])) {
                $tmpEvolutionChainLink = $this->createEvolutionChainLinkFrom(
                    $arrayChainEvolveTo[$index], $language, $evolutionChain, $evolutionChainLink, $evolutionLevel
                );
                $index++;
                // We need to change stage
                if (!isset($arrayChainEvolveTo[$index])) {
                    // reset the index
                    $index = 0;
                    // increase the evolution level
                    $evolutionLevel++;
                    // reset the evolution chain link : we take an other lvl of evolution
                    $evolutionChainLink = $tmpEvolutionChainLink;
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
     * @param EvolutionChainLink|null $evolutionChainLink
     * @param int $evolutionLevel
     * @return null
     * @throws NonUniqueResultException
     */
    private function createEvolutionChainLinkFrom(
        $urlEvolutionChain,
        Language $language,
        EvolutionChain $evolutionChain,
        ?EvolutionChainLink $evolutionChainLink,
        int $evolutionLevel
    ) {
        // Fetch the pokemon-species from the species api json
        $pokemonSpecies = $this->pokemonSpeciesManager->getSimplePokemonSpeciesBySlug(
            $this->textManager->generateSlugFromClassWithLanguage(
                $language, PokemonSpecies::class, $urlEvolutionChain['species']['name']
            )
        );
        // Set the pokemon species to the starting evolution chain
        $pokemonSpecies->setEvolutionChain($evolutionChain);
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
        ;
        // set the new evolution chain link to the previous level of evolution chain link
        $evolutionChainLink->addEvolutionChainLink($newEvolutionChainLink);
        $this->entityManager->persist($newEvolutionChainLink);
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
            $knownMoveType = $this->typeManager->getTypeBySlug(
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
            $partyType = $this->typeManager->getTypeBySlug(
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