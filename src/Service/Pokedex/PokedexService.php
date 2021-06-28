<?php


namespace App\Service\Pokedex;


use App\Entity\Pokedex\Pokedex;
use App\Entity\Pokedex\PokedexSpecies;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonSpeciesService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Locations\RegionRepository;
use App\Repository\Pokedex\PokedexRepository;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokedexService extends AbstractService
{
    /**
     * @var PokedexRepository
     */
    private PokedexRepository $pokedexRepository;

    /**
     * @var VersionGroupService $versionGroupService
     */
    private VersionGroupService $versionGroupService;

    /**
     * @var PokemonSpeciesService $pokemonSpeciesService
     */
    private PokemonSpeciesService $pokemonSpeciesService;

    /**
     * @var RegionRepository $regionRepo
     */
    private RegionRepository $regionRepo;

    /**
     * @var GenerationRepository $generationRepo
     */
    private GenerationRepository $generationRepo;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityService
     * @param PokedexRepository $pokedexRepository
     * @param RegionRepository $regionRepo
     * @param VersionGroupService $versionGroup
     * @param PokemonSpeciesService $pokemonSpeciesService
     * @param GenerationRepository $generationRepo
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        PokedexRepository $pokedexRepository,
        RegionRepository $regionRepo,
        VersionGroupService $versionGroup,
        PokemonSpeciesService $pokemonSpeciesService,
        GenerationRepository $generationRepo,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService
    )
    {
        $this->versionGroupService = $versionGroup;
        $this->regionRepo = $regionRepo;
        $this->generationRepo = $generationRepo;
        $this->pokedexRepository = $pokedexRepository;
        $this->pokemonSpeciesService = $pokemonSpeciesService;
        parent::__construct($entityService, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return Pokedex|null
     */
    private function getPokedexBySlug(string $slug): ?Pokedex
    {
        return $this->pokedexRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlPokedexDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        //Check if the pokedex exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language,
            Pokedex::class,
            $urlPokedexDetailed['name']
        );

        if (null === $this->getPokedexBySlug($slug))
        {
            $codeLang = $language->getCode();
            // Fetch name & description according the language
            $pokedexName = $this->apiService->getNameBasedOnLanguageFromArray(
                $codeLang,
                $urlPokedexDetailed
            );
            $pokedex = (new Pokedex())
                ->setName(ucfirst($pokedexName))
                ->setSlug($slug)
                ->setLanguage($language)
                ->setDescription($this->apiService->getFieldContentFromLanguage(
                        $codeLang, $urlPokedexDetailed, 'descriptions',  'description'
                    )
                );

            // Add the GroupVersion
            if (sizeof($urlPokedexDetailed['version_groups']) > 0)
            {
                foreach($urlPokedexDetailed['version_groups'] as $versionGroupName)
                {
                    $versionGroup = $this->versionGroupService->getArrayVersionGroup(
                        $language
                    )[$codeLang.'/version-group-'.$versionGroupName['name']];
                    $pokedex->addVersionGroup($versionGroup);
                }
            }
            $this->entityManager->persist($pokedex);

            // Create the link between pokedexes and the pokemon species number inside the pokedex
            if (!empty($urlPokedexDetailed['pokemon_entries'])) {
                foreach($urlPokedexDetailed['pokemon_entries'] as $pokemonSpeciesName)
                {
                    $pokemonSpecies = $this->pokemonSpeciesService->getPokemonSpeciesBySlug(
                        $this->textService->generateSlugFromClassWithLanguage(
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
            if (null !== $urlPokedexDetailed['region']) {
                $urlDetailedRegion = $this->apiService->apiConnect($urlPokedexDetailed['region']['url'])->toArray();
                $region = $this->regionRepo->findOneBySlug($language->getCode().'/region-'.$urlDetailedRegion['name']);
                $generation = $this->generationRepo->findOneBy([
                    'mainRegion' => $region
                ]);

                if ($generation !== null) {
                    $pokedex->setGeneration($generation);
                }
            }
        }
        $this->entityManager->flush();
    }

}