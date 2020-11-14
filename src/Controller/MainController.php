<?php


namespace App\Controller;


use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    /**
     * @Route(path="/", name="home")
     */
    function home(): Response {

        $client = HttpClient::create();

        $nomPokmn = "salamence";

        $url = "https://pokeapi.co/api/v2/pokemon/${nomPokmn}";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new \RuntimeException(sprintf('The API return an error.'));
        }

        $apiResponse = $response->toArray();

        var_dump($apiResponse);

        return $this->render('base.html.twig', [
            'apiResponse' => $apiResponse
        ]);
    }
}