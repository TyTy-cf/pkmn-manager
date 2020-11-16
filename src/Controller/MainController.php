<?php


namespace App\Controller;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Manager\AbilitiesManager;
use App\Manager\PokemonManager;
use App\Manager\TypeManager;
use App\Repository\AbilitiesRepository;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MainController extends AbstractController
{

    /**
     * @var AbilitiesManager $abilitiesManager
     */
    private $abilitiesManager;

    /**
     * @var TypeManager
     */
    private $typeManager;

    /**
     * @var PokemonManager
     */
    private $pokemonManager;

    /**
     * AlbumManager constructor.
     *
     * @param AbilitiesManager $abilitiesManager
     * @param TypeManager $typeManager
     * @param PokemonManager $pokemonManager
     */
    public function __construct(AbilitiesManager $abilitiesManager, TypeManager $typeManager, PokemonManager $pokemonManager)
    {
        $this->abilitiesManager = $abilitiesManager;
        $this->typeManager = $typeManager;
        $this->pokemonManager = $pokemonManager;
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
        return $this->render('index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    /**
     * Display pokemon list
     *
     * @Route(path="/listing/{offset}", name="listing")
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    function listing(Request $request): Response
    {
        $offset = $request->get('offset');

        //Récupération de la pagination
        if ($offset < 20) {
            $offset = 0;
        }

        //Connexion à l'API pour récupération des données
        $client = HttpClient::create();
        $url = "https://pokeapi.co/api/v2/pokemon/?offset=${offset}&limit=20";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        //Récupération des données dans un tableau
        $apiResponse = $response->toArray();

        //Récupération du pokéName
        foreach ($apiResponse['results'] as $i) {
            $namesPokmn[] = $i['name'];
        }

        return $this->render('listing.html.twig', [
            'namesPokmn' => $namesPokmn,
            'offset' => $offset
        ]);
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{pokeName}", name="profile_pokemon")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    function profile(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $pokemonName = $request->get('pokeName');

        // Check if the pokemon exists inside the database
        if (($pokemon = $this->pokemonManager->findByName($pokemonName)) == null) {

            // not existing, we are looking for it in the API
            $client = HttpClient::create();
            $url = "https://pokeapi.co/api/v2/pokemon/".$pokemonName;
            $response = $client->request('GET', $url);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(sprintf('The API return an error.'));
            }

            // save the Json
            $apiResponse = $response->toArray();

            // Create the new pokemon
            $pokemon = $this->pokemonManager->saveNewPokemon($apiResponse, $pokemonName);
        }

        return $this->render('profile.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}

