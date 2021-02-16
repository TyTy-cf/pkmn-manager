<?php


namespace App\Service\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemHeldPokemon;
use App\Entity\Items\ItemHeldPokemonVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonService;
use App\Service\TextService;
use App\Service\Versions\VersionService;
use Doctrine\ORM\EntityManagerInterface;

class ItemHeldPokemonService extends AbstractService
{

    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * @var VersionService $versionManager
     */
    private VersionService $versionManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param PokemonService $pokemonManager
     * @param VersionService $versionManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextService $textManager,
        PokemonService $pokemonManager,
        VersionService $versionManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->versionManager = $versionManager;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param Item $item
     * @param $urlDetailed
     */
    public function createItemHeldPokemon(Language $language, Item $item, $urlDetailed) {
        foreach($urlDetailed['held_by_pokemon'] as $pokemonInfos) {
            $pokemon = $this->pokemonManager->getPokemonBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language, Pokemon::class, $pokemonInfos['pokemon']['name']
                )
            );
            $itemHeldPokemon = (new ItemHeldPokemon())
                ->setPokemon($pokemon)
                ->setItem($item)
            ;
            $this->entityManager->persist($itemHeldPokemon);

            // Récupère les versions
            foreach($pokemonInfos['version_details'] as $versionDetail) {
                $versions = $this->versionManager->getArrayVersions($language);
                $itemHeldPokemonVersion = (new ItemHeldPokemonVersion())
                    ->setRarity($versionDetail['rarity'])
                    ->setItemHeldByPokemon($itemHeldPokemon)
                    ->setVersion(
                        $versions[$language->getCode().'/version-'.$versionDetail['version']['name']]
                    );
                $this->entityManager->persist($itemHeldPokemonVersion);
            }
        }
    }
}
