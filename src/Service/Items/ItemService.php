<?php


namespace App\Service\Items;


use App\Entity\Items\Item;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonService;
use App\Service\TextService;
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
     * @var PokemonService $pokemonService
     */
    private PokemonService $pokemonService;

    /**
     * @var ItemHeldPokemonService $itemHeldPokemonService
     */
    private ItemHeldPokemonService $itemHeldPokemonService;

    /**
     * @var ItemDescriptionService $itemDescriptionService
     */
    private ItemDescriptionService $itemDescriptionService;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonService $pokemonService
     * @param ItemHeldPokemonService $itemHeldPokemonService
     * @param ItemDescriptionService $itemDescriptionService
     * @param ApiService $apiService
     * @param TextService $textService
     * @param ItemRepository $itemRepo
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonService $pokemonService,
        ItemHeldPokemonService $itemHeldPokemonService,
        ItemDescriptionService $itemDescriptionService,
        ApiService $apiService,
        TextService $textService,
        ItemRepository $itemRepo
    ) {
        $this->itemRepo = $itemRepo;
        $this->pokemonService =$pokemonService;
        $this->itemDescriptionService = $itemDescriptionService;
        $this->itemHeldPokemonService = $itemHeldPokemonService;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();

        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, Item::class, $apiResponse['name']
        );

        if (null === $this->itemRepo->findOneBySlug($slug)) {
            // Fetch name & description according the language
            $itemNameLang = $this->apiService->getNameBasedOnLanguageFromArray(
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
                    $this->itemDescriptionService->createItemDescription(
                        $language, $newItem, $urlDetailed
                    );
                }

                // Get all the pokemon able to held the item
                if (isset($urlDetailed['held_by_pokemon'])) {
                    $this->itemHeldPokemonService->createItemHeldPokemon(
                        $language, $newItem, $urlDetailed
                    );
                }

                $this->entityManager->persist($newItem);
                $this->entityManager->flush();
            }
        }
    }
}
