<?php


namespace App\Controller\Pokedex;


use App\Entity\Pokedex\PokedexSpecies;
use App\Entity\Versions\Generation;
use App\Manager\Pokedex\PokedexManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Pokedex\PokedexSpeciesRepository;
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

        $arrayPokedex = [];
        $pokedexes = $this->pokedexManager->getPokedexByGeneration(
            $generation, $language
        );
        foreach($pokedexes as $pokedex) {
            $arrayPokedex[] = [
                'pokedex' => $pokedex,
                'pokemons' => $this->pokemonManager->getPokemonsByPokedex($pokedex),
            ];
        }
        return $this->render('Pokedex/listing.html.twig', [
            'arrayPokedex' => $arrayPokedex,
        ]);
    }

}
