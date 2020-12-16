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

        // Check and create if necessary the evolution chain
        // Can be empty, unecessary to even create the evolution_chain starting
        if (sizeof($urlPokemonSpecies['chain']['evolves_to']) > 0) {
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
                ->setSlug($this->textManager->generateSlugFromClassWithLanguage(
                    $language, EvolutionChain::class, $idApi
                )
            );
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
            $evolutionChainLink = (new EvolutionChainLink())
                ->setIsBaby($arrayChainEvolveTo['is_baby'])
                ->setCurrentPokemonSpecies($startingSpecies)
                ->setOrder($evolutionLevel)
            ;
            $this->entityManager->persist($evolutionChainLink);
            $evolutionChain->setEvolutionChainLink($evolutionChainLink);
            $startingSpecies->setEvolutionChain($evolutionChain);
            // Create the rest of the link
            $index = 0;
            $evolutionLevel++;
            $arrayChainEvolveTo = $arrayChainEvolveTo['evolves_to'];
            while (isset($arrayChainEvolveTo[$index])) {
                $evolutionChainLink = $this->createEvolutionChainLinkFrom(
                    $arrayChainEvolveTo[$index], $language, $evolutionChain, $evolutionChainLink, $evolutionLevel
                );
                $index++;
                // We need to change stage
                if (!isset($arrayChainEvolveTo[$index])) {
                    $index = 0;
                    $evolutionLevel++;
                    $arrayChainEvolveTo = $arrayChainEvolveTo[$index]['evolves_to'];
                }
            }
        }
        $this->entityManager->flush();
        die();
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
        $pokemonSpecies = $this->pokemonSpeciesManager->getSimplePokemonSpeciesBySlug(
            $this->textManager->generateSlugFromClassWithLanguage(
                $language, PokemonSpecies::class, $urlEvolutionChain['species']['name']
            )
        );
        $pokemonSpecies->setEvolutionChain($evolutionChain);
        $newEvolutionChainLink = (new EvolutionChainLink())
            ->setIsBaby($urlEvolutionChain['is_baby'])
            ->setEvolutionDetail($this->getEvolutionDetailFromJson($urlEvolutionChain['evolution_details'][0], $language))
            ->setCurrentPokemonSpecies($pokemonSpecies)
            ->setOrder($evolutionLevel)
        ;
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

        // Fetch gender
        $gender = null;
        if ($urlEvolutionChainDetailed['gender'] !== null) {
            $gender = $this->genderRepository->findOneBySlug(
                $language->getCode().'/'.Gender::$relationMap[$urlEvolutionChainDetailed['gender']]
            );
        }
        $heldItem = null;
        if ($urlEvolutionChainDetailed['held_item'] !== null) {
            $heldItem = $this->itemManager->getItemBySlug(
                $language->getCode().'/item-'.$urlEvolutionChainDetailed['held_item']['name']
            );
        }
        $item = null;
        if ($urlEvolutionChainDetailed['item'] !== null) {
            $item = $this->itemManager->getItemBySlug(
                $language->getCode().'/item-'.$urlEvolutionChainDetailed['item']['name']
            );
        }
        $knownMove = null;
        if ($urlEvolutionChainDetailed['known_move'] !== null) {
            $knownMove = $this->moveManager->getSimpleMoveBySlug(
                $language->getCode().'/move-'.$urlEvolutionChainDetailed['known_move']['name']
            );
        }
        $knownMoveType = null;
        if ($urlEvolutionChainDetailed['known_move_type'] !== null) {
            $knownMoveType = $this->typeManager->getTypeBySlug(
                $language->getCode().'/type-'.$urlEvolutionChainDetailed['known_move_type']['name']
            );
        }
        $location = null;
        if ($urlEvolutionChainDetailed['location'] !== null) {
            $location = $this->locationManager->getLocationBySlug(
                $language->getCode().'/location-'.$urlEvolutionChainDetailed['location']['name']
            );
        }
        $partySpecies = null;
        if ($urlEvolutionChainDetailed['party_species'] !== null) {
            $partySpecies = $this->pokemonSpeciesManager->getPokemonSpeciesBySlug(
                $language->getCode() . '/pokemon-species-' . $urlEvolutionChainDetailed['party_species']['name']
            );
        }
        $partyType = null;
        if ($urlEvolutionChainDetailed['party_type'] !== null) {
            $partyType = $this->typeManager->getTypeBySlug(
                $language->getCode() . '/type-' . $urlEvolutionChainDetailed['party_type']['name']
            );
        }
        $evolutionTrigger = null;
        if ($urlEvolutionChainDetailed['trigger'] !== null) {
            $evolutionTrigger = $this->evolutionTriggerManager->getEvolutionTriggerBySlug(
                $language->getCode() . '/evolution-trigger-' . $urlEvolutionChainDetailed['trigger']['name']
            );
        }
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
