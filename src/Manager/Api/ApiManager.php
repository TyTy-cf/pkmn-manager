<?php


namespace App\Manager\Api;


use App\Entity\Pokemon\Pokemon;
use App\Manager\Pokemon\PokemonManager;
use http\Exception\RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPokemonsListing($offset)
    {
        //Connexion à l'API pour récupération des données
        $apiResponse = $this->apiConnect("https://pokeapi.co/api/v2/pokemon/?offset=${offset}&limit=42");

        //Récupération du pokéName
        foreach ($apiResponse['results'] as $result) {
            $pokemonNames[] = $result['name'];
        }
        return $pokemonNames;
    }

    /**
     * Connection to the API and retrieving JSON information
     * @param $url
     * @return mixed
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function apiConnect($url)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            throw new RuntimeException(sprintf('The API return an error.'));
        }

        return $response->toArray();
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
    public function getPokemonFromName($pokemonName)
    {
        // Check if the pokemon exists inside the database
        if (($pokemon = $this->pokemonManager->findByName($pokemonName)) == null) {
            // not existing, we are looking for it in the API
            $apiResponse = $this->apiConnect("https://pokeapi.co/api/v2/pokemon/" . $pokemonName);

            // Create the new pokemon
            $pokemon = $this->pokemonManager->saveNewPokemon($apiResponse, $pokemonName);
        }
        return $pokemon;
    }

    /**
     * Return from API the information of the $ability passed in parameter
     * @param $url
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAbilitiesDetailed($url)
    {
        $apiResponse = $this->apiConnect($url);

        $abilitiesDetailed = [
            'nameAbilityFr' => $apiResponse['names']['3']['name'],
            'descriptionAbilityFr' => $apiResponse['flavor_text_entries']['19']['flavor_text'],
        ];

        dump($abilitiesDetailed);

        die();


        return $abilitiesDetailed;

    }
}