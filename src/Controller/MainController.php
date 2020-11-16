<?php


namespace App\Controller;

use App\Entity\Pokemon\Pokemon;
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
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    function profile(Request $request): Response
    {
        $nomPokmn = $request->get('pokeName');

        $pokemon =




        $client = HttpClient::create();


        $url = "https://pokeapi.co/api/v2/pokemon/${nomPokmn}";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        $apiResponse = $response->toArray();

        //Stockage des données souhaitées
        //Image
        $imgPokmn = $apiResponse['sprites']['other']['dream_world']['front_default'];

        //Abilities
        foreach ($apiResponse['abilities'] as $i) {
            $abilityNames[] = $i['ability']['name'];
        }

        //Types
        foreach ($apiResponse['types'] as $i) {
            $typeNames[] = $i['type']['name'];
        }

        //Spéciales statistiques
        foreach ($apiResponse['stats'] as $i) {
            $namesSpecialStats[] = $i['stat']['name'];
            $valeursSpecialStats[] = $i['base_stat'];
        }

        return $this->render('profile.html.twig', [
            'apiResponse' => $apiResponse,
            'nomPokmn' => $nomPokmn,
            'imgPokmn' => $imgPokmn,
            'abilityNames' => $abilityNames,
            'typeNames' => $typeNames,
            'namesSpecialStats' => $namesSpecialStats,
            'valeursSpecialStats' => $valeursSpecialStats,
        ]);
    }
}