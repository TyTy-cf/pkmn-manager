<?php


namespace App\Controller\Pokedex;


use App\Entity\Versions\Generation;
use App\Service\Pokedex\PokedexService;
use App\Service\Pokemon\PokemonService;
use App\Service\Users\LanguageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokedexController extends AbstractController
{

    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * @var LanguageService $languageManager
     */
    private LanguageService $languageManager;

    /**
     * @var PokedexService $pokedexManager
     */
    private PokedexService $pokedexManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonManager
     * @param PokedexService $pokedexManager
     * @param LanguageService $languageManager
     */
    public function __construct
    (
        PokemonService $pokemonManager,
        PokedexService $pokedexManager,
        LanguageService $languageManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->pokedexManager = $pokedexManager;
        $this->languageManager = $languageManager;
    }

    /**
     * Display pokemon list
     *
     * @Route(path="/pokedex/{slug_generation}", name="pokedex_list_generation", requirements={"slug_generation": ".+"})
     * @ParamConverter(class="App\Entity\Versions\Generation", name="generation", options={"mapping": {"slug_generation" : "slug"}})
     *
     * @param Request $request
     * @param Generation $generation
     * @return Response
     */
    function listing(Request $request, Generation $generation): Response
    {
        $arrayPokedex = [];
        $pokedexes = $this->pokedexManager->getPokedexByGeneration(
            $generation, $this->languageManager->getLanguageByCode('fr')
        );
        foreach($pokedexes as $pokedex) {
            $arrayPokedex[] = [
                'pokedex' => $pokedex,
                'pokemons' => $this->pokemonManager->getPokemonsByPokedex($pokedex),
            ];
        }
        return $this->render('Pokedex/listing.html.twig', [
            'arrayPokedex' => $arrayPokedex,
            'generation' => $generation,
        ]);
    }

}
