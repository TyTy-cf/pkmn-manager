<?php


namespace App\Service\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemDescription;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Items\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ItemService extends AbstractService
{

    /**
     * @var ItemRepository
     */
    private ItemRepository $itemRepo;

    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * @var ItemHeldPokemonService $itemHeldPokemonManager
     */
    private ItemHeldPokemonService $itemHeldPokemonManager;

    /**
     * @var ItemDescriptionService $itemDescriptionManager
     */
    private ItemDescriptionService $itemDescriptionManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonService $pokemonManager
     * @param ItemHeldPokemonService $itemHeldPokemonService
     * @param ItemDescriptionService $itemDescriptionService
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param ItemRepository $itemRepo
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonService $pokemonManager,
        ItemHeldPokemonService $itemHeldPokemonService,
        ItemDescriptionService $itemDescriptionService,
        ApiService $apiManager,
        TextService $textManager,
        ItemRepository $itemRepo
    )
    {
        $this->itemRepo = $itemRepo;
        $this->pokemonManager =$pokemonManager;
        $this->itemDescriptionManager = $itemDescriptionService;
        $this->itemHeldPokemonManager = $itemHeldPokemonService;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Item|null
     */
    public function getItemBySlug(string $slug): ?object
    {
        return $this->itemRepo->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();

        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Item::class, $apiResponse['name']
        );

        if ($this->getItemBySlug($slug) === null) {
            // Fetch name & description according the language
            $itemNameLang = $this->apiManager->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );

            if ($itemNameLang !== null) {
                $newItem = (new Item())
                    ->setName($itemNameLang)
                    ->setSlug($slug)
                    ->setCost($urlDetailed['cost'])
                    ->setLanguage($language)
                    ->setSpriteUrl($urlDetailed['sprites']['default'])
                ;

                if (isset($urlDetailed['flavor_text_entries'])) {
                    $this->itemDescriptionManager->createItemDescription(
                        $language, $newItem, $urlDetailed
                    );
                }

                // Get all the pokemon able to held the item
                if (isset($urlDetailed['held_by_pokemon'])) {
                    $this->itemHeldPokemonManager->createItemHeldPokemon(
                        $language, $newItem, $urlDetailed
                    );
                }

                $this->entityManager->persist($newItem);
                $this->entityManager->flush();
            }
        }
    }
}
