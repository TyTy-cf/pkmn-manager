<?php


namespace App\Manager\Pokedex;


use App\Entity\Locations\Region;
use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokedex\PokedexSpecies;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonSpeciesManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Location\RegionRepository;
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
     * @var RegionRepository $regionRepo
     */
    private RegionRepository $regionRepo;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokedexRepository $pokedexRepository
     * @param RegionRepository $regionRepo
     * @param VersionGroupManager $versionGroup
     * @param PokemonSpeciesManager $pokemonSpeciesManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokedexRepository $pokedexRepository,
        RegionRepository $regionRepo,
        VersionGroupManager $versionGroup,
        PokemonSpeciesManager $pokemonSpeciesManager,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->versionGroupManager = $versionGroup;
        $this->regionRepo = $regionRepo;
        $this->pokedexRepository = $pokedexRepository;
        $this->pokemonSpeciesManager = $pokemonSpeciesManager;
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
     * @return int|mixed|string
     */
    public function getAllPokedexDetailed(Language $language)
    {
        return $this->pokedexRepository->getAllPokedexDetailed($language);
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

        if (($pokedex = $this->getPokedexBySlug($slug)) === null)
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
                    $versionGroup = $this->versionGroupManager->getArrayVersionGroup(
                        $language
                    )[$codeLang.'/version-group-'.$versionGroupName['name']];
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
            if (!empty($urlPokedexDetailed['pokemon_entries'])) {
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
            if ($urlPokedexDetailed['region'] !== null) {
                $urlDetailedRegion = $this->apiManager->getDetailed($urlPokedexDetailed['region']['url'])->toArray();
                $slug = $this->textManager->generateSlugFromClassWithLanguage(
                    $language, Region::class, $urlDetailedRegion['name']
                );
                if ($this->regionRepo->findOneBySlug($slug) === null) {
                    // Create the region
                    $region = (new Region())
                        ->setSlug($slug)
                        ->setLanguage($language)
                        ->setName(
                            $this->apiManager->getNameBasedOnLanguageFromArray($language->getCode(), $urlDetailedRegion)
                        )
                    ;
                    $pokedex->setRegion($region);
                    $this->entityManager->persist($region);
                }
            }
        }
        $this->entityManager->flush();
    }

}