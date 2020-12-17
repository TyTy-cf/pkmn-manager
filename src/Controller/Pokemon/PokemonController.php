<?php


namespace App\Controller\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\PokemonMovesLearnVersionManager;
use App\Manager\Pokedex\EvolutionChainManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{

    /**
     * @var PokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var PokemonMovesLearnVersionManager $pokemonMoveManager
     */
    private PokemonMovesLearnVersionManager $pokemonMoveManager;

    /**
     * @var EvolutionChainManager $evolutionChainManager
     */
    private EvolutionChainManager $evolutionChainManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     * @param EvolutionChainManager $evolutionChainManager
     * @param PokemonMovesLearnVersionManager $pokemonMoveManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        ApiManager $apiManager,
        EvolutionChainManager $evolutionChainManager,
        PokemonMovesLearnVersionManager $pokemonMoveManager,
        LanguageManager $languageManager
    )
    {
        $this->pokemonMoveManager = $pokemonMoveManager;
        $this->evolutionChainManager = $evolutionChainManager;
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{slug_pokemon}", name="profile_pokemon", requirements={"slug_pokemon": ".+"})
     * @ParamConverter(class="App\Entity\Pokemon\Pokemon", name="pokemon", options={"mapping": {"slug_pokemon" : "slug"}})
     *
     * @param Request $request
     * @param Pokemon $pokemon
     * @return Response
     * @throws NonUniqueResultException
     */
    function displayProfile(Request $request, Pokemon $pokemon): Response
    {
        $this->evolutionChainManager->generateEvolutionChainFromPokemon($pokemon);
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pokemonMoveManager->generateArrayMovesForPokemon($pokemon),
//            'evolutionChain' => $this->evolutionChainManager->generateEvolutionChainFromPokemon($pokemon),
        ]);
    }

    /**
     * @Route(path="/pokemons/getAll", name="get_all_pokemon_names")
     *
     * @return JsonResponse
     */
    function getAllPokemonNamesJson(): JsonResponse
    {
        return new JsonResponse($this->pokemonManager->getAllPokemonNameForLanguage(
            $this->languageManager->getLanguageByCode('fr')
        ));
    }
}
