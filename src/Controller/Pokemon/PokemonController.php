<?php


namespace App\Controller\Pokemon;

use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use App\Repository\Pokemon\PokemonSpritesVersionRepository;
use App\Service\Infos\Type\TypeService;
use App\Service\Pokedex\EvolutionChainService;
use App\Service\Pokemon\PokemonService;
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
     * @var LanguageService $languageService
     */
    private LanguageService $languageService;

    /**
     * @var EvolutionChainService $evolutionChainService
     */
    private EvolutionChainService $evolutionChainService;

    /**
     * @var PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository
     */
    private PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository;

    /**
     * @var PokemonSpritesVersionRepository $pokemonSpritesVersionRepository
     */
    private PokemonSpritesVersionRepository $pokemonSpritesVersionRepository;

    /**
     * @var PokemonRepository $pokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * @var TypeService $typeService
     */
    private TypeService $typeService;

    /**s
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonService
     * @param EvolutionChainService $evolutionChainService
     * @param LanguageService $languageService
     * @param PokemonRepository $pokemonRepository
     * @param PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository
     * @param PokemonSpritesVersionRepository $pokemonSpritesVersionRepository
     * @param TypeService $typeService
     */
    public function __construct(
        PokemonService $pokemonService,
        EvolutionChainService $evolutionChainService,
        LanguageService $languageService,
        PokemonRepository $pokemonRepository,
        PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository,
        PokemonSpritesVersionRepository $pokemonSpritesVersionRepository,
        TypeService $typeService
    ) {
        $this->evolutionChainService = $evolutionChainService;
        $this->pokemonRepository = $pokemonRepository;
        $this->pokemonService = $pokemonService;
        $this->typeService = $typeService;
        $this->languageService = $languageService;
        $this->pokemonSpeciesVersionRepository = $pokemonSpeciesVersionRepository;
        $this->pokemonSpritesVersionRepository = $pokemonSpritesVersionRepository;
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
        $pokemon = $this->pokemonRepository->getPokemonProfileBySlug($request->get('slug_pokemon'));
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pokemonService->getArrayMovesByVersionForPokemon($pokemon),
            'arrayEvolutionChain' => $this->evolutionChainService->getEvolutionChainFromPokemon($pokemon),
            'arrayDescriptionVersion' => $this->pokemonSpeciesVersionRepository->getDescriptionVersionByVersionsAndPokemon(
                $pokemon->getPokemonSpecies()
            ),
            'spritesVersionGroup' => $this->pokemonSpritesVersionRepository->getSpritesVersionGroupByPokemon($pokemon),
            'typesRelation' => $this->typeService->getTypesWeaknessesByPokemon($pokemon),
            'types' => $this->typeService->getAllTypeByLanguage($pokemon->getLanguage()),
        ]);
    }

    /**
     * @Route(path="/pokemons/searchPokemonByName/{approxName}", name="get_pokemon_by_name")
     *
     * @param string $approxName
     * @return JsonResponse
     */
    function searchPokemonByName(string $approxName): JsonResponse {
        return new JsonResponse($this->pokemonRepository->getPokemonNameForLanguage(
            $this->languageService->getLanguageByCode('fr'),
            $approxName
        ));
    }
}
