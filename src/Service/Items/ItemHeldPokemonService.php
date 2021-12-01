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
     * @var PokemonService $pokemonService
     */
    private PokemonService $pokemonService;

    /**
     * @var VersionService $versionService
     */
    private VersionService $versionService;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param PokemonService $pokemonService
     * @param VersionService $versionService
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        PokemonService $pokemonService,
        VersionService $versionService
    ) {
        $this->pokemonService = $pokemonService;
        $this->versionService = $versionService;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param Item $item
     * @param $urlDetailed
     */
    public function createItemHeldPokemon(Language $language, Item $item, $urlDetailed) {
        foreach($urlDetailed['held_by_pokemon'] as $pokemonInfos) {
            $pokemon = $this->pokemonService->getPokemonBySlug(
                $this->textService->generateSlugFromClassWithLanguage(
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
                $versions = $this->versionService->getVersionsByLanguage($language);
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
