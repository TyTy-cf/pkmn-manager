<?php


namespace App\Manager\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemDescription;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Items\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ItemManager extends AbstractManager
{

    /**
     * @var ItemRepository
     */
    private ItemRepository $itemRepo;

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ItemHeldPokemonManager $itemHeldPokemonManager
     */
    private ItemHeldPokemonManager $itemHeldPokemonManager;

    /**
     * @var ItemDescriptionManager $itemDescriptionManager
     */
    private ItemDescriptionManager $itemDescriptionManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonManager $pokemonManager
     * @param ItemHeldPokemonManager $itemHeldPokemonManager
     * @param ItemDescriptionManager $itemDescriptionManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param ItemRepository $itemRepo
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonManager $pokemonManager,
        ItemHeldPokemonManager $itemHeldPokemonManager,
        ItemDescriptionManager $itemDescriptionManager,
        ApiManager $apiManager,
        TextManager $textManager,
        ItemRepository $itemRepo
    )
    {
        $this->itemRepo = $itemRepo;
        $this->pokemonManager =$pokemonManager;
        $this->itemDescriptionManager = $itemDescriptionManager;
        $this->itemHeldPokemonManager = $itemHeldPokemonManager;
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
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();

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
