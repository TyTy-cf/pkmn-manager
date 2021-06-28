<?php


namespace App\Controller\Pokedex;


use App\Entity\Versions\Generation;
use App\Repository\Pokedex\PokedexRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Service\Users\LanguageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokedexController extends AbstractController
{

    /**
     * @var LanguageService $languageService
     */
    private LanguageService $languageService;

    /**
     * @var PokemonRepository $pokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * @var PokedexRepository $pokedexRepository
     */
    private PokedexRepository $pokedexRepository;

    /**
     * PokemonController constructor.
     *
     * @param PokedexRepository $pokedexRepository
     * @param LanguageService $languageManager
     * @param PokemonRepository $pokemonRepository
     */
    public function __construct(
        PokedexRepository $pokedexRepository,
        LanguageService $languageManager,
        PokemonRepository $pokemonRepository
    ) {
        $this->pokemonRepository = $pokemonRepository;
        $this->pokedexRepository = $pokedexRepository;
        $this->languageService = $languageManager;
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
        $pokedexes = $this->pokedexRepository->getPokedexByGeneration(
            $generation, $this->languageService->getLanguageByCode('fr')
        );
        foreach($pokedexes as $pokedex) {
            $arrayPokedex[] = [
                'pokedex' => $pokedex,
                'pokemons' => $this->pokemonRepository->getPokemonsByPokedex($pokedex),
            ];
        }
        return $this->render('Pokedex/listing.html.twig', [
            'arrayPokedex' => $arrayPokedex,
            'generation' => $generation,
        ]);
    }

}
