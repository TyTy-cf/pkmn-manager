<?php


namespace App\Controller\Pokedex;


use App\Entity\Versions\Generation;
use App\Manager\Pokedex\PokedexManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokedexController extends AbstractController
{

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var PokedexManager $pokedexManager
     */
    private PokedexManager $pokedexManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param PokedexManager $pokedexManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        PokedexManager $pokedexManager,
        LanguageManager $languageManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->pokedexManager = $pokedexManager;
        $this->languageManager = $languageManager;
    }

    /**
     * Display pokemon list
     *
     * @Route(path="/pokemons/{slug_generation}", name="pokemon_list_generation", requirements={"slug_generation": ".+"})
     * @ParamConverter(class="App\Entity\Versions\Generation", name="generation", options={"mapping": {"slug_generation" : "slug"}})
     *
     * @param Request $request
     * @param Generation $generation
     * @return Response
     */
    function listing(Request $request, Generation $generation): Response
    {
        $language = $this->languageManager->getLanguageByCode('fr');
        return $this->render('Pokemon/listing.html.twig', [
            'pokedex' => $this->pokedexManager->getPokedexByRegion($generation->getMainRegion(), $language),
            'pokemons' => $this->pokemonManager->getPokemonsByGenerationAndLanguage($generation, $language),
        ]);
    }

}
