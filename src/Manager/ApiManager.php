<?php


namespace App\Manager;


use App\Entity\Pokemon\Pokemon;
use http\Exception\RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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
     * @var PokemonManager
     */
    private $pokemonManager;

    /**
     * ApiManager constructor.
     *
     * @param PokemonManager $pokemonManager
     */
    public function __construct(PokemonManager $pokemonManager)
    {
        $this->pokemonManager = $pokemonManager;
    }

    /**
     * @param $offset
     * @return mixed
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPokemonsListing($offset) {
        //Connexion à l'API pour récupération des données
        $client = HttpClient::create();
        $url = "https://pokeapi.co/api/v2/pokemon/?offset=${offset}&limit=42";

        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        //Récupération des données dans un tableau
        $apiResponse = $response->toArray();

        //Récupération du pokéName
        foreach ($apiResponse['results'] as $result) {
            $pokemonNames[] = $result['name'];
        }
        return $pokemonNames;
    }

    /**
     * Return from the API the informations of the $pokemonName passed in parameter
     *
     * @param $pokemonName
     *
     * @return Pokemon|object|null
     *
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPokemonFromName($pokemonName) {
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
        return $pokemon;
    }
}