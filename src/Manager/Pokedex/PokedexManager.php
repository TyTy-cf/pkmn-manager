<?php


namespace App\Manager\Pokedex;


use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokedex\PokedexSpecies;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonSpeciesManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Pokedex\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokedexManager extends AbstractManager
{
    /**
     * @var PokedexRepository
     */
    private PokedexRepository $pokedexRepository;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * @var PokemonSpeciesManager $pokemonSpeciesManager
     */
    private PokemonSpeciesManager $pokemonSpeciesManager;

    /**
     * @var array $arrayVersionGroup
     */
    private static array $arrayVersionGroup;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokedexRepository $pokedexRepository
     * @param VersionGroupManager $versionGroup
     * @param PokemonSpeciesManager $pokemonSpeciesManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokedexRepository $pokedexRepository,
        VersionGroupManager $versionGroup,
        PokemonSpeciesManager $pokemonSpeciesManager,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->versionGroupManager = $versionGroup;
        $this->pokedexRepository = $pokedexRepository;
        $this->pokemonSpeciesManager = $pokemonSpeciesManager;
        self::$arrayVersionGroup = array();
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Pokedex|null
     */
    private function getPokedexBySlug(string $slug)
    {
        return $this->pokedexRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @return Version[]|array
     */
    public function getArrayVersionsGroup(Language $language)
    {
        if (empty(self::$arrayVersionGroup))
        {
            $tmpArrayVersions = $this->versionGroupManager->getAllVersionGroupByLanguage($language);
            foreach($tmpArrayVersions as $version)
            {
                self::$arrayVersionGroup[$version->getSlug()] = $version;
            }
        }
        return self::$arrayVersionGroup;
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlPokedexDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();

        //Check if the pokedex exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language,
            Pokedex::class,
            $urlPokedexDetailed['name']
        );

        if ($this->getPokedexBySlug($slug) === null)
        {
            $codeLang = $language->getCode();
            // Fetch name & description according the language
            $pokedexName = $this->apiManager->getNameBasedOnLanguageFromArray(
                $codeLang,
                $urlPokedexDetailed
            );
            $pokedex = (new Pokedex())
                ->setName(ucfirst($pokedexName))
                ->setSlug($slug)
                ->setLanguage($language)
                ->setDescription($this->apiManager->getFieldContentFromLanguage(
                        $codeLang, $urlPokedexDetailed, 'descriptions',  'description'
                    )
                );

            // Add the GroupVersion
            if (sizeof($urlPokedexDetailed['version_groups']) > 0)
            {
                foreach($urlPokedexDetailed['version_groups'] as $versionGroupName)
                {
                    $versionGroup = $this->getArrayVersionsGroup($language)[$codeLang.'/version-group-'.$versionGroupName['name']];
                    $pokedex->addVersionGroup($versionGroup);
                }
            }
            $this->entityManager->persist($pokedex);

            // Create the link between pokedexes and the pokemon species number inside the pokedex
            if (sizeof($urlPokedexDetailed['version_groups']) > 0)
            {
                foreach($urlPokedexDetailed['pokemon_entries'] as $pokemonSpeciesName)
                {
                    $pokemonSpecies = $this->pokemonSpeciesManager->getPokemonSpeciesBySlug(
                        $this->textManager->generateSlugFromClassWithLanguage(
                            $language,
                            PokemonSpecies::class,
                            $pokemonSpeciesName['pokemon_species']['name']
                        )
                    );
                    $pokedexSpecies = (new PokedexSpecies())
                        ->setNumber($pokemonSpeciesName['entry_number'])
                        ->setPokedex($pokedex)
                        ->setPokemonSpecies($pokemonSpecies)
                    ;
                    $this->entityManager->persist($pokedexSpecies);
                }
            }

            $this->entityManager->flush();
        }
    }

}