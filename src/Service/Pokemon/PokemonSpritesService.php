<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSprites;
use App\Service\TextService;
use App\Repository\Pokemon\PokemonSpritesRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class PokemonSpritesManager
 * @package App\Service\Pokemon
 *
 * @property PokemonSpritesRepository $pokemonSpriteRepo
 */
class PokemonSpritesService
{

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
     * @param TextService $textManager
     * @param $urlDetail
     */
    public function createPokemonSprites(
        EntityManagerInterface $entityManager,
        Pokemon $pokemon,
        TextService $textManager,
        $urlDetail
    ) {
        $slug = 'pokemon-sprites-' . $textManager->slugify($pokemon->getNameApi());

        if (null === $pokemonSprite = $this->getPokemonSpritesBySlug($slug)) {
            $pokemonSprite = (new PokemonSprites())->setSlug($slug);
            $entityManager->persist($pokemonSprite);
        }
        $pokemonSprite
            ->setSpriteFrontDefault($urlDetail['front_default'])
            ->setSpriteFrontShiny($urlDetail['front_shiny'])
            ->setSpriteFrontFemale($urlDetail['front_female'])
            ->setSpriteFemaleShiny($urlDetail['front_shiny_female'])
            ->setSpriteOfficial($urlDetail['other']['official-artwork']['front_default'])
            ->setSpriteIcon($urlDetail['versions']['generation-viii']['icons']['front_default'])
        ;
        $pokemon->setPokemonSprites($pokemonSprite);
    }
}
