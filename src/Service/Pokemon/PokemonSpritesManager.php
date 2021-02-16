<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSprites;
use App\Service\TextManager;
use App\Repository\Pokemon\PokemonSpritesRepository;
use Doctrine\ORM\EntityManagerInterface;

class PokemonSpritesManager
{
    /**
     * @var PokemonSpritesRepository $pokemonSpriteRepo
     */
    private PokemonSpritesRepository $pokemonSpriteRepo;

    /**
     * PokemonSpritesManager constructor.
     * @param PokemonSpritesRepository $pokemonSpriteRepo
     */
    public function __construct(PokemonSpritesRepository $pokemonSpriteRepo)
    {
        $this->pokemonSpriteRepo = $pokemonSpriteRepo;
    }

    /**
     * @param string $slug
     * @return PokemonSprites|object|null
     */
    private function getPokemonSpritesBySlug(string $slug)
    {
        return $this->pokemonSpriteRepo->findOneBySlug($slug);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Pokemon $pokemon
     * @param TextManager $textManager
     * @param $urlDetail
     */
    public function createPokemonSprites(
        EntityManagerInterface $entityManager,
        Pokemon $pokemon,
        TextManager $textManager,
        $urlDetail
    ) {
        $slug = $textManager->generateSlugFromClass(
            PokemonSprites::class, $pokemon->getNameApi()
        );

        if (($pokemonSprite = $this->getPokemonSpritesBySlug($slug)) === null) {
            $pokemonSprite = (new PokemonSprites())
                ->setSpriteFrontDefault($urlDetail['front_default'])
                ->setSpriteFrontShiny($urlDetail['front_shiny'])
                ->setSpriteFrontFemale($urlDetail['front_female'])
                ->setSpriteFemaleShiny($urlDetail['front_shiny_female'])
                ->setSpriteOfficial($urlDetail['other']['official-artwork']['front_default'])
                ->setSpriteIcon($urlDetail['versions']['generation-viii']['icons']['front_default'])
                ->setSlug($slug)
            ;
            $entityManager->persist($pokemonSprite);
        }
        $pokemon->setPokemonSprites($pokemonSprite);
    }
}
