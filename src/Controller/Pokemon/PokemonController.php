<?php


namespace App\Controller\Pokemon;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Form\SearchPokemonType;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

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
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        ApiManager $apiManager,
        LanguageManager $languageManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
    }

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->redirectToRoute('listing', array('offset' => 0));
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

    /**
     * Display pokemon list
     *
     * @Route(path="/pokemons/{offset}", name="listing")
     *
     * @param Request $request
     * @return Response
     *
     */
    function listing(Request $request): Response
    {
        //Création du formulaire de recherche
        $searchPokemonForm = $this->createForm(SearchPokemonType::class);
        $searchPokemonForm->handleRequest($request);

        //Si formulaire est soumis ET valide
        if ($searchPokemonForm->isSubmitted() && $searchPokemonForm->isValid()) {
            $namePokemon = $searchPokemonForm->getData();
            $pokemon = $this->pokemonManager->getPokemonByNameAndLanguage(
                $namePokemon['name_pokemon'], $this->languageManager->getLanguageByCode('fr')
            );
            return $this->redirectToRoute('profile_pokemon', ['slug' => $pokemon->getSlug()]);
        }

        //Affichage de la liste
        $offset = $request->get('offset');
        $limit = 150;
        //Récupération de la pagination
        if ($offset < 150) {
            $offset = 0;
        }

        $pokemonsList = $this->pokemonManager->getPokemonOffsetLimitByLanguage(
            $this->languageManager->getLanguageByCode('fr'), $offset, $limit
        );

        //Données pour autocompletion
        $jsonAllPokemon = $this->getAllPokemonNamesJson();

        return $this->render('Pokemon/listing.html.twig', [
            'pokemonsList' => $pokemonsList,
            'formSearchPokemon' => $searchPokemonForm->createView(),
            'offset' => $offset,
            'jsonAllPokemon' => $jsonAllPokemon,
        ]);
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{slug}", name="profile_pokemon", requirements={"slug": ".+"})
     *
     * @param Request $request
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    function displayProfile(Request $request): Response
    {
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $this->pokemonManager->getPokemonAndSpeciesBySlug($request->get('slug')),
        ]);
    }
}
