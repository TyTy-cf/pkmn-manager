<?php


namespace App\Manager\Api;


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
        return $this->apiConnect('https://pokeapi.co/api/v2/pokemon/' . $pokemonName);
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
    public function getAllPokemonJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/pokemon/?offset=0&limit=1117');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllTypeJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/type');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllNatureJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/nature?offset=0&limit=25');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllAbilitiesJson()
    {
        return $this->apiConnect('http://pokeapi.co/api/v2/ability/?offset=0&limit=299');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllDamageClassJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/move-damage-class/');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllGenerationJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/generation/');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllVersionGroupJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/version-group/');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllVersionJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/version?offset=0&limit=34');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllMoveLearnMethodJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/move-learn-method/');
    }

    /**
     *
     * @throws TransportExceptionInterface
     */
    public function getAllMoveJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/move/?offset=0&limit=813');
    }

    /**
     *
     * @throws TransportExceptionInterface
     */
    public function getAllPokemonSpeciesJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/pokemon-species/?offset=0&limit=898');
    }

    /**
     *
     * @throws TransportExceptionInterface
     */
    public function getAllMoveMachineJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/machine/?offset=0&limit=1442');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getEggGroupJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/egg-group/');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllPokedexJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/pokedex?offset=0&limit=28');
    }

    /**
     * @return mixed
     * @throws TransportExceptionInterface
     */
    public function getAllItemsJson()
    {
        return $this->apiConnect('https://pokeapi.co/api/v2/item?offset=0&limit=954');
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
        return $this->getNameBasedOnLanguageFromArray($lang, $apiResponse);
    }

    /**
     * @param $lang
     *
     * @param $apiResponse
     * @return string
     */
    public function getNameBasedOnLanguageFromArray($lang, $apiResponse): ?string
    {
        return $this->getFieldContentFromLanguage($lang, $apiResponse, 'names', 'name');
    }

    /**
     * @param string $lang
     * @param $apiResponse
     * @return string|null
     */
    public function getFlavorTextBasedOnLanguageFromArray(string $lang, $apiResponse): ?string
    {
        return $this->getFieldContentFromLanguage($lang, $apiResponse, 'flavor_text_entries', 'flavor_text');
    }

    /**
     * @param $lang
     *
     * @param $apiResponse
     * @param $mainField
     * @param $field
     * @return string
     */
    public function getFieldContentFromLanguage(string $lang, $apiResponse, string $mainField, string $field): ?string
    {
        $description = null;
        if (sizeof($apiResponse) > 0) {
            foreach ($apiResponse[$mainField] as $flavor_text_entry) {
                if ($flavor_text_entry['language']['name'] === $lang) {
                    $description = $flavor_text_entry[$field];
                    break;
                }
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