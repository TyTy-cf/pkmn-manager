<?php


namespace App\Controller\Pokemon;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Form\SearchPokemonType;
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

    const POKEMON_NAMES_SESSION = 'pokemonNamesSession';

    /**
     * @var PokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     * @param SessionInterface $session
     */
    public function __construct
    (
        PokemonManager $pokemonManager,
        ApiManager $apiManager,
        SessionInterface $session
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        $this->session = $session;
    }

    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="/", name="index")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $pokemons = $paginator->paginate(
            $this->pokemonManager->findBy(['id' => 'DESC']),
            $request->query->getInt('page', '1'),
            8
        );

        return $this->render('Pokemon/index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    /**
     * @Route(path="/pokemons/getAll", name="get_all_pokemon_names")
     *
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    function getAllPokemonNamesJson(): JsonResponse
    {
        $pokemonNames = array ();
        //Vérification si la variable existe
        if ($this->session->get(PokemonController::POKEMON_NAMES_SESSION) == null) {
            $apiResponse = $this->apiManager->getAllPokemonJson();
            $apiResponse = $apiResponse->toArray();
            //Récupération du pokéName
            foreach ($apiResponse['results'] as $result) {
                $pokemonNames[] = $result['name'];
            }
            $this->session->set(PokemonController::POKEMON_NAMES_SESSION, $pokemonNames);
        } else {
            $pokemonNames = $this->session->get(PokemonController::POKEMON_NAMES_SESSION);
        }
        return new JsonResponse($pokemonNames);
    }

    /**
     * Display pokemon list
     *
     * @Route(path="/pokemons/{offset}", name="listing")
     *
     * @param Request $request
     * @return Response
     *
     * @throws TransportExceptionInterface
     */
    function listing(Request $request): Response
    {

        //Création du formulaire de recherche
        $searchPokemonForm = $this->createForm(SearchPokemonType::class);
        $searchPokemonForm->handleRequest($request);

        //Si formulaire est soumis ET valide
        if ($searchPokemonForm->isSubmitted() && $searchPokemonForm->isValid()) {
            $nameSearchPoke = $searchPokemonForm->getData();
            return $this->redirectToRoute('profile_pokemon', ['pokeName' => $nameSearchPoke['name_pokemon']]);
        }

        //Affichage de la liste
        $offset = $request->get('offset');

        //Récupération de la pagination
        if ($offset < 42) {
            $offset = 0;
        }

        $apiResponse = $this->apiManager->getPokemonsListing($offset);
        $apiResponse = $apiResponse->toArray();

        //Récupération du pokéName
        foreach ($apiResponse['results'] as $result) {
            $pokemonNames[] = $result['name'];
        }

        //Données pour autocompletion
        $jsonAllPokemon = $this->getAllPokemonNamesJson();

        return $this->render('Pokemon/listing.html.twig', [
            'pokemonNames' => $pokemonNames,
            'formSearchPokemon' => $searchPokemonForm->createView(),
            'offset' => $offset,
            'jsonAllPokemon' => $jsonAllPokemon,
        ]);
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{pokeName}", name="profile_pokemon")
     *
     * @param Request $request
     * @return Response
     *
     */
    function displayProfile(Request $request): Response
    {
        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon = $this->pokemonManager->findByName($request->get('pokeName')),
        ]);
    }
}
