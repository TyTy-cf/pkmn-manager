<?php


namespace App\Manager\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemHeldPokemon;
use App\Entity\Items\ItemHeldPokemonVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemHeldPokemonManager extends AbstractManager
{

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var VersionManager $versionManager
     */
    private VersionManager $versionManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param PokemonManager $pokemonManager
     * @param VersionManager $versionManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        PokemonManager $pokemonManager,
        VersionManager $versionManager
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
