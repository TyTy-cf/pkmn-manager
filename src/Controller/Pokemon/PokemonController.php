<?php


namespace App\Controller\Pokemon;

use App\Service\Api\ApiService;
use App\Service\Pokedex\EvolutionChainService;
use App\Service\Pokemon\PokemonService;
use App\Service\Pokemon\PokemonSpeciesVersionService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{

    /**
     * @var PokemonService
     */
    private PokemonService $pokemonService;

    /**
     * @var ApiService
     */
    private ApiService $apiService;

    /**
     * @var LanguageService $languageService
     */
    private LanguageService $languageService;

    /**
     * @var EvolutionChainService $evolutionChainService
     */
    private EvolutionChainService $evolutionChainService;

    /**
     * @var PokemonSpeciesVersionService $pokemonSpeciesVersionService
     */
    private PokemonSpeciesVersionService $pokemonSpeciesVersionService;

    /**
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonService
     * @param ApiService $apiService
     * @param EvolutionChainService $evolutionChainService
     * @param LanguageService $languageService
     * @param PokemonSpeciesVersionService $pokemonSpeciesVersionService
     */
    public function __construct (
        PokemonService $pokemonService,
        ApiService $apiService,
        EvolutionChainService $evolutionChainService,
        LanguageService $languageService,
        PokemonSpeciesVersionService $pokemonSpeciesVersionService
    ) {
        $this->evolutionChainService = $evolutionChainService;
        $this->pokemonSpeciesVersionService = $pokemonSpeciesVersionService;
        $this->pokemonService = $pokemonService;
        $this->apiService = $apiService;
        $this->languageService = $languageService;
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{slug_pokemon}", name="profile_pokemon", requirements={"slug_pokemon": ".+"})
     *
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    function displayProfile(Request $request): Response
    {
        $pokemon = $this->pokemonService->getPokemonProfileBySlug($request->get('slug_pokemon'));
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pokemonService->generateArrayByVersionForPokemon($pokemon),
            'arrayEvolutionChain' => $this->evolutionChainService->generateEvolutionChainFromPokemon($pokemon),
            'arrayDescriptionVersion' => $this->pokemonSpeciesVersionService->getDescriptionVersionByVersionsAndPokemon(
                $pokemon->getPokemonSpecies()
            ),
            'arraySprites' => $this->pokemonService->getSpritesArrayByPokemon($pokemon)
        ]);
    }

    /**
     * @Route(path="/pokemons/getAll", name="get_all_pokemon_names")
     *
     * @return JsonResponse
     */
    function getAllPokemonNamesJson(): JsonResponse
    {
        return new JsonResponse($this->pokemonService->getAllPokemonNameForLanguage(
            $this->languageService->getLanguageByCode('fr')
        ));
    }
}
