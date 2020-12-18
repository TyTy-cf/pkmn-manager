<?php


namespace App\Controller\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Pokedex\EvolutionChainManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Pokemon\PokemonSpeciesVersionManager;
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
     * @var EvolutionChainManager $evolutionChainManager
     */
    private EvolutionChainManager $evolutionChainManager;

    /**
     * @var PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     */
    private PokemonSpeciesVersionManager $pokemonSpeciesVersionManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     * @param EvolutionChainManager $evolutionChainManager
     * @param LanguageManager $languageManager
     * @param PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        ApiManager $apiManager,
        EvolutionChainManager $evolutionChainManager,
        LanguageManager $languageManager,
        PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
    )
    {
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
