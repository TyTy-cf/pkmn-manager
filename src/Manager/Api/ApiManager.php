<?php


namespace App\Manager\Api;


use App\Entity\Pokemon\Pokemon;
use http\Exception\RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ApiManager
 * @package App\Manager
 *
 * Manage all the datas informations between the application and the API
 * @see https://pokeapi.co
 */
class ApiManager
{

    /**
     * Connection to the API and retrieving JSON information
     * @param $url
     * @return mixed
     * @throws TransportExceptionInterface
     */
    private function apiConnect($url)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        return $response;
    }

    /**
     * @param $offset
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getPokemonsListing($offset)
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/pokemon/?offset=${offset}&limit=42");

    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getPokemonJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/pokemon/?offset=0&limit=649");
    }

    /**
     * Return from the API the informations of the $pokemonName passed in parameter
     *
     * @param $pokemonName
     *
     * @return Pokemon|object|null
     *
     * @throws TransportExceptionInterface
     */
    public function getPokemonFromName($pokemonName)
    {

        return $this->apiConnect("https://pokeapi.co/api/v2/pokemon/" . $pokemonName);
    }

    /**
     * Return from API the information of the $var passed in parameter
     * @param $url
     * @return array
     * @throws TransportExceptionInterface
     */
    public function getDetailed($url)
    {
        return $this->apiConnect($url);
    }

}