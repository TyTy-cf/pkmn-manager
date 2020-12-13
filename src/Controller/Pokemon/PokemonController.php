<?php


namespace App\Controller\Pokemon;

use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\PokemonMovesLearnVersionManager;
use App\Manager\Pokemon\PokemonManager;
use App\Form\SearchPokemonType;
use App\Manager\Users\LanguageManager;
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
     * @var PokemonMovesLearnVersionManager $pkmnMoveManager
     */
    private PokemonMovesLearnVersionManager $pkmnMoveManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     * @param PokemonMovesLearnVersionManager $pkmnMoveManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        ApiManager $apiManager,
        PokemonMovesLearnVersionManager $pkmnMoveManager,
        LanguageManager $languageManager
    )
    {
        $this->pkmnMoveManager = $pkmnMoveManager;
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
     */
    function displayProfile(Request $request, Pokemon $pokemon): Response
    {
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pkmnMoveManager->generateArrayMovesForPokemon($pokemon, $pokemon->getLanguage()),
        ]);
    }

    /**
     * @Route(path="/pokemon/search", name="pokemon_search")
     *
     * @param Request $request
     * @return Response
     */
    function searchPokemon(Request $request): Response {
        $language = $this->languageManager->getLanguageByCode('fr');
        // Création du formulaire de recherche
        $searchPokemonForm = $this->createForm(SearchPokemonType::class);
        $searchPokemonForm->handleRequest($request);

        //Si formulaire est soumis ET valide
        if ($searchPokemonForm->isSubmitted() && $searchPokemonForm->isValid()) {
            $pokemon = $this->pokemonManager->getPokemonByNameAndLanguage(
                $searchPokemonForm->getData()['name_pokemon'], $language
            );
            return $this->redirectToRoute('profile_pokemon', ['slug' => $pokemon->getSlug()]);
        }

        return $this->render('Pokemon/listing.html.twig', [
            'pokemonsList' => $this->pokemonManager->getAllPokemonsListByLanguage($language),
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
