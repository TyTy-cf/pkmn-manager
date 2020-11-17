<?php


namespace App\Controller\Pokemon;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function __construct(PokemonManager $pokemonManager, ApiManager $apiManager)
    {
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
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
        $offset = $request->get('offset');

        //Récupération de la pagination
        if ($offset < 42) {
            $offset = 0;
        }

        $pokemonNames = $this->apiManager->getPokemonsListing($offset);

        return $this->render('Pokemon/listing.html.twig', [
            'pokemonNames' => $pokemonNames,
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
    function displayProfile(Request $request): Response {

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

