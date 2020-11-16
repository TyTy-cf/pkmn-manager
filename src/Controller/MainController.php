<?php


namespace App\Controller;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use App\Repository\AbilitiesRepository;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
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
     * Affiche l'index
     * @Route (path="/", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * Affiche la liste des pokémons consultables
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

        if ($offset < 20) {
            $offset = 0;
        }

        $client = HttpClient::create();
        $url = "https://pokeapi.co/api/v2/pokemon/?offset=${offset}&limit=20";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

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

            //Si absent =>Connexion à l'API
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

            //Récupération des statistiques spéciales
            foreach ($apiResponse['stats'] as $i) {
                if ($i['stat']['name'] == 'hp') {
                    $pokemon->setHp($i['base_stat']);
                } elseif ($i['stat']['name'] == 'attack') {
                    $pokemon->setAtk($i['base_stat']);
                } elseif ($i['stat']['name'] == 'defense') {
                    $pokemon->setDef($i['base_stat']);
                } elseif ($i['stat']['name'] == 'special-attack') {
                    $pokemon->setSpa($i['base_stat']);
                } elseif ($i['stat']['name'] == 'special-defense') {
                    $pokemon->seSpd($i['base_stat']); // / ! \ SeSpd à corriger !
                } elseif ($i['stat']['name'] == 'speed') {
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
                    $em->flush();
                }

            }

            //Vérification si les Types sont présents dans la BDD
            foreach ($apiResponse['types'] as $i) {

                if ($typeRepository->findBy(['name' => $i['type']['name']]) == null) {

                    $type = new Type();
                    $type->setName($i['type']['name']);

                    $pokemon->addType($type->getId());

                    $em->persist($type);
                    $em->flush();
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

