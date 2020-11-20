<?php


namespace App\Controller\Pokemon;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Form\SearchPokemonType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonController extends AbstractController
{

    /**
     * @var PokemonManager
     */
    private $pokemonManager;

    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * PokemonController constructor.
     *
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     */
    public function __construct(PokemonManager $pokemonManager, ApiManager $apiManager, SessionInterface $session)
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
            $this->pokemonManager->findby(['id' => 'DESC']),
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
     */
    function getAllPokemonNamesJson(): JsonResponse
    {
        //Vérification si la variable existe
        if ($this->session->get('pokename') == null) {

            if (!session_status()) {
                new Session();
                $this->session->start();
            }

            $apiResponse = $this->apiManager->getDetailed('https://pokeapi.co/api/v2/pokemon/?offset=0&limit=1050');
            $apiResponse = $apiResponse->toArray();

            //Récupération du pokéName
            foreach ($apiResponse['results'] as $result) {
                $pokemonNames[] = $result['name'];
            }
            $this->session->set('pokename', $pokemonNames);
        }
        // Traitement pour récupérer TOUS les noms de pokemons
        // Il peut être intéressant de les stocker en session, afin de ne pas retourner faire des appels sur l'API constamment
        // - Ajouter un SessionManager au constructeur ici
        // - Ajouter une constante "ALL_POKEMON_NAMES" pour la valeur stockée en Session
        // - Tester en 1er lieu ici si la valeur de la session est null ou pas, si elle n'est pas null on la récupère et on squizze l'étape de l'API
        // Regarde la doc de JsonResponse pour voir comment passer tes noms dans la response

        // Le code JS doit être écrit dans : pkmn_listing.js
        //
        return new JsonResponse(['ALL_POKEMON_NAMES' => $this->session->get('pokename')]);
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
            return $this->redirectToRoute('profile_pokemon', ['pokeName' => $nameSearchPoke['namePokemon']]);
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

        return $this->render('Pokemon/listing.html.twig', [
            'pokemonNames' => $pokemonNames,
            'formSearchPokemon' => $searchPokemonForm->createView(),
            'offset' => $offset
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
     * @throws TransportExceptionInterface
     */
    function displayProfile(Request $request): Response
    {

        $pokemonName = $request->get('pokeName');

        // Check if the pokemon exists inside the database
        if (($pokemon = $this->pokemonManager->findByName($pokemonName)) == null) {
            // Not existing, we are looking for it in the API
            $apiResponse = $this->apiManager->getPokemonFromName($pokemonName)->toArray();
            // And create the new pokemon
            $pokemon = $this->pokemonManager->saveNewPokemon($apiResponse, $pokemonName);
        }

        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
