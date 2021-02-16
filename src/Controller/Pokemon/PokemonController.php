<?php


namespace App\Controller\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Service\Api\ApiService;
use App\Service\Pokedex\EvolutionChainService;
use App\Service\Pokemon\PokemonService;
use App\Service\Pokemon\PokemonSpeciesVersionManager;
use App\Service\Users\LanguageService;
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
     * @var PokemonService
     */
    private PokemonService $pokemonManager;

    /**
     * @var ApiService
     */
    private ApiService $apiManager;

    /**
     * @var LanguageService $languageManager
     */
    private LanguageService $languageManager;

    /**
     * @var EvolutionChainService $evolutionChainManager
     */
    private EvolutionChainService $evolutionChainManager;

    /**
     * @var PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     */
    private PokemonSpeciesVersionManager $pokemonSpeciesVersionManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonManager
     * @param ApiService $apiManager
     * @param EvolutionChainService $evolutionChainManager
     * @param LanguageService $languageManager
     * @param PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     */
    public function __construct (
        PokemonService $pokemonManager,
        ApiService $apiManager,
        EvolutionChainService $evolutionChainManager,
        LanguageService $languageManager,
        PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
    ) {
        $this->evolutionChainManager = $evolutionChainManager;
        $this->pokemonSpeciesVersionManager = $pokemonSpeciesVersionManager;
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
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
        $pokemon = $this->pokemonManager->getPokemonProfileBySlug($request->get('slug_pokemon'));
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pokemonManager->generateArrayByVersionForPokemon($pokemon),
            'arrayEvolutionChain' => $this->evolutionChainManager->generateEvolutionChainFromPokemon($pokemon),
            'arrayDescriptionVersion' => $this->pokemonSpeciesVersionManager->getDescriptionVersionByVersionsAndPokemon(
                $pokemon->getPokemonSpecies()
            ),
            'arraySprites' => $this->pokemonManager->getSpritesArrayByPokemon($pokemon)
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
