<?php


namespace App\Controller;

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
     * Affiche l'index
     * @Route (path="/", name="index")
     */
    public function index() {
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

        return $this->render('listing.html.twig', [
            'apiResponse' => $apiResponse,
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

        $client = HttpClient::create();
        $nomPokmn = $request->get('pokeName');
        $url = "https://pokeapi.co/api/v2/pokemon/${nomPokmn}";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        $apiResponse = $response->toArray();

        return $this->render('profile.html.twig', [
            'apiResponse' => $apiResponse,
        ]);
    }
}