<?php


namespace App\Manager\Api;


use App\Entity\Pokemon\Pokemon;
use http\Exception\RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Validator\Constraints\Json;
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
    public function getAllPokemonJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/pokemon/?offset=0&limit=1117");
    }

    /**
     * Return from the API the informations of the $pokemonName passed in parameter
     *
     * @param $pokemonName
     *
     * @return mixed
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
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getDetailed($url)
    {
        return $this->apiConnect($url);
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllTypeJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/type");
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllNatureJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/nature?offset=0&limit=25");
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllAbilitiesJson()
    {
        return $this->apiConnect("http://pokeapi.co/api/v2/ability/?offset=0&limit=299");
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllDamageClassJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/move-damage-class/");
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllGenerationJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/generation/");
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllVersionGroupJson()
    {
        return $this->apiConnect("https://pokeapi.co/api/v2/version-group/");
    }

    /**
     * @param $lang
     * @param $url
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getNameBasedOnLanguage($lang, $url)
    {
        $apiResponse = $this->getDetailed($url)->toArray();
        return $this->getNameBasedOnLanguageFromArray($lang, $apiResponse['names']);
    }

    /**
     * @param $lang
     *
     * @param $apiResponse
     * @return string
     */
    public function getNameBasedOnLanguageFromArray($lang, $apiResponse): ?string
    {
        $nameReturned = null;
        foreach ($apiResponse as $name) {
            if ($name['language']['name'] === $lang) {
                $nameReturned = $name['name'];
            }
        }
        return $nameReturned;
    }

    /**
     * @param string $lang
     * @param $apiResponse
     * @return string|null
     */
    public function getFlavorTextBasedOnLanguageFromArray(string $lang, $apiResponse): ?string
    {
        $description = null;
        foreach ($apiResponse['flavor_text_entries'] as $flavor_text_entry) {
            if ($flavor_text_entry['language']['name'] === $lang) {
                $description = $flavor_text_entry['flavor_text'];
                break;
            }
        }
        return $description;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function getIdFromUrl($url)
    {
        $splittedUrl = explode('/', $url);
        return $splittedUrl[6];
    }

}