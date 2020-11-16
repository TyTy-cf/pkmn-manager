<?php


namespace App\Controller;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
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
     * Affiche l'index et les derniers pokémons enregistrés dans la base de données
     * @Route (path="/", name="index")
     * @param Request $request
     * @param PokemonRepository $pokemonRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(
        Request $request,
        PokemonRepository $pokemonRepository,
        PaginatorInterface $paginator
    ): Response {
        $pokemons = $paginator->paginate(
            $pokemonRepository->findby([], ['id' => 'DESC']),
            $request->query->getInt('page', '1'),
            8);

        return $this->render('index.html.twig', [
            'pokemons' => $pokemons
        ]);
    }

    /**
     * Affiche la liste des pokémons consultables en ligne
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
        $namesPokmn = [];
        foreach ($apiResponse['results'] as $i) {
            $namesPokmn[] = $i['name'];
        }

        return $this->render('listing.html.twig', [
            'namesPokmn' => $namesPokmn,
            'offset' => $offset,
        ]);
    }

    /**
     * Affiche les caractéristiques d'un pokemon
     * @Route(path="/pokemon/{pokeName}", name="profile_pokemon")
     * @param Request $request
     * @param PokemonRepository $pokemonRepository
     * @param AbilitiesRepository $abilitiesRepository
     * @param TypeRepository $typeRepository
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
        PokemonRepository $pokemonRepository,
        AbilitiesRepository $abilitiesRepository,
        TypeRepository $typeRepository,
        EntityManagerInterface $em
    ): Response {
        $nomPokmn = $request->get('pokeName');

        //Vérification si le Pokémon est présent dans la base de données
        if ($pokemonRepository->findBy(['name' => $nomPokmn]) == null) {

            //Si absent de la BDD => Récupếération des données via l'API
            $client = HttpClient::create();
            $url = "https://pokeapi.co/api/v2/pokemon/${nomPokmn}";
            $response = $client->request('GET', $url);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(sprintf('The API return an error.'));
            }

            //Stockage des données dans variable
            $apiResponse = $response->toArray();

            //Création d'un nouvel objet
            $pokemon = new Pokemon();
            $pokemon->setName(ucfirst($nomPokmn));
            $pokemon->setUrlimg($apiResponse['sprites']['other']['dream_world']['front_default']);

            //Récupération des statistiques spéciales
            foreach ($apiResponse['stats'] as $i) {

                switch ($i['stat']['name']) {
                    case 'hp':
                        $pokemon->setHp($i['base_stat']);
                        break;
                    case 'attack':
                        $pokemon->setAtk($i['base_stat']);
                        break;
                    case 'defense':
                        $pokemon->setDef($i['base_stat']);
                        break;
                    case 'special-attack':
                        $pokemon->setSpa($i['base_stat']);
                        break;
                    case 'special-defense':
                        $pokemon->seSpd($i['base_stat']); // / ! \ SeSpd à corriger !
                        break;
                    case 'speed':
                        $pokemon->setSpe($i['base_stat']);

                }
            }

            //Vérification si les Abilitiés sont présents dans la BDD
            foreach ($apiResponse['abilities'] as $i) {

                if ($abilitiesRepository->findBy(['name' => $i['ability']['name']]) == null) {
                    $abilitie = new Abilities();
                    $abilitie->setName($i['ability']['name']);
                    $abilitie->setDescription('En attendant de trouver une description !');

                    $em->persist($abilitie);
                    $pokemon->addAbility($abilitie);
                    $em->flush();

                } else {
                    $abilitie = $abilitiesRepository->findOneBy(['name' => $i['ability']['name']]);
                    $pokemon->addAbility($abilitie);
                }
            }

            //Vérification si les Types sont présents dans la BDD
            foreach ($apiResponse['types'] as $i) {

                if ($typeRepository->findBy(['name' => $i['type']['name']]) == null) {

                    $type = new Type();
                    $type->setName($i['type']['name']);
                    $em->persist($type);
                    $pokemon->addType($type);
                    $em->flush();
                } else {
                    $type = $typeRepository->findOneBy(['name' => $i['type']['name']]);
                    $pokemon->addType($type);
                }
            }

            $em->persist($pokemon);
            $em->flush();
        }

        //Récupération des données dans la base
        $pokemon = $pokemonRepository->findOneBy(['name' => $nomPokmn]);

        return $this->render('profile.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
