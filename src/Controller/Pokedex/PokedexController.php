<?php


namespace App\Controller\Pokedex;


use App\Entity\Versions\Version;
use App\Manager\Api\ApiManager;
use App\Manager\Pokedex\PokedexManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\VersionGroupManager;
use App\Manager\Versions\VersionManager;
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
     * @Route(path="/pokedex", name="pokedex_index")
     *
     * @param Request $request
     * @return Response
     */
    public function pokedexIndex(Request $request): Response {
        $pokedex = $this->pokedexManager->getAllPokedexDetailed(
            $this->languageManager->getLanguageByCode('fr')
        );
        return $this->render('Pokedex/index.html.twig', [
            'pokedexList' => $pokedex,
        ]);
    }

    /**
     * @Route(path="/pokedex/{slug}", name="pokedex_detailed", requirements={"slug": ".+"})
     *
     * @param Request $request
     * @return Response
     */
    public function pokedexShow(Request $request): Response {
        
    }
}
