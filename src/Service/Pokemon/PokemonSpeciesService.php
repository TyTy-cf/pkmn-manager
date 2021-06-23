<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Repository\Pokedex\EggGroupRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\GenerationService;
use App\Service\Versions\VersionService;
use App\Repository\Pokemon\PokemonSpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpeciesService extends AbstractService
{
    /**
     * @var PokemonService
     */
    private PokemonService $pokemonService;

    /**
     * @var PokemonSpeciesRepository
     */
    private PokemonSpeciesRepository $repository;

    /**
     * @var PokemonSpeciesVersionService
     */
    private PokemonSpeciesVersionService $pokemonSpeciesVersionService;

    /**
     * @var EggGroupRepository $eggGroupRepository
     */
    private EggGroupRepository $eggGroupRepository;

    /**
     * @var VersionService $versionService
     */
    private VersionService $versionService;

    /**
     * @var GenerationService $generationService
     */
    private GenerationService $generationService;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param PokemonService $pokemonService
     * @param EggGroupRepository $eggGroupRepository
     * @param VersionService $versionService
     * @param GenerationService $generationService
     * @param PokemonSpeciesVersionService $pokemonSpeciesVersionService
     * @param PokemonSpeciesRepository $repository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        PokemonService $pokemonService,
        EggGroupRepository $eggGroupRepository,
        VersionService $versionService,
        GenerationService $generationService,
        PokemonSpeciesVersionService $pokemonSpeciesVersionService,
        PokemonSpeciesRepository $repository
    ) {
        $this->repository = $repository;
        $this->eggGroupRepository = $eggGroupRepository;
        $this->generationService = $generationService;
        $this->pokemonService = $pokemonService;
        $this->versionService = $versionService;
        $this->pokemonSpeciesVersionService = $pokemonSpeciesVersionService;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return PokemonSpecies|null
     */
    public function getPokemonSpeciesBySlug(string $slug): ?PokemonSpecies
    {
        return $this->repository->findOneBySlug($slug);
    }

    /**
     * @param string $slug
     * @return PokemonSpecies|null
     * @throws NonUniqueResultException
     */
    public function getSimplePokemonSpeciesBySlug(string $slug): ?PokemonSpecies
    {
        return $this->repository->getSimplePokemonSpeciesBySlug($slug);
    }

    /**
     * Save a new pokemon from API to the database
     * Create his abilities and type(s) if necessary
     *
     * @param Language $language
     * @param $apiResponse
     * @return void
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, PokemonSpecies::class, $apiResponse['name']
        );

        if (null === $this->getPokemonSpeciesBySlug($slug))
        {
            $codeLang = $language->getCode();
            $pokemonSpeciesName = $this->apiService->getNameBasedOnLanguageFromArray(
                $codeLang, $urlDetailed
            );
            $genera = $this->apiService->getFieldContentFromLanguage(
                $codeLang, $urlDetailed, 'genera',  'genus'
            );

            $pokemonSpecies = (new PokemonSpecies())
                ->setGenera($genera)
                ->setLanguage($language)
                ->setName($pokemonSpeciesName)
                ->setSlug($codeLang.'/'.$slug)
                ->setGrowthRate($urlDetailed['growth_rate']['name'])
                ->setIsMythical($urlDetailed['is_mythical'])
                ->setIsBaby($urlDetailed['is_baby'])
                ->setIsLegendary($urlDetailed['is_legendary'])
                ->setBaseHappiness($urlDetailed['base_happiness'])
                ->setCaptureRate($urlDetailed['capture_rate'])
                ->setHasGenderDifferences($urlDetailed['has_gender_differences'])
            ;

            if (isset($urlDetailed['evolves_from_species']))
            {
                // Set le evolve from species
                $pokemonSpecies->setEvolvesFromSpecies(
                    $this->getPokemonSpeciesBySlug(
                        $this->textService->generateSlugFromClassWithLanguage(
                            $language, PokemonSpecies::class, $urlDetailed['evolves_from_species']['name']
                        )
                    )
                );
            }

            // Set the egg(s) group
            foreach($urlDetailed['egg_groups'] as $eggGroupName)
            {
                $eggGroup = $this->eggGroupRepository->findOneBySlug(
                    $language->getCode().'/egg-group-' . $eggGroupName['name']
                );
                if ($eggGroup !== null)
                {
                    $pokemonSpecies->addEggGroup($eggGroup);
                }
            }

            $this->entityManager->persist($pokemonSpecies);

            // Finally get the pokemon linked to this species and update it
            $pokemon = $this->pokemonService->getPokemonBySlug(
                $this->textService->generateSlugFromClassWithLanguage(
                    $language,Pokemon::class, $urlDetailed['varieties'][0]['pokemon']['name']
                )
            );

            $pokemon->setPokemonSpecies($pokemonSpecies);
            $this->entityManager->persist($pokemon);

            // Set PokemonSpeciesVersion
            $this->pokemonSpeciesVersionService->createFromApi(
                $language,
                $urlDetailed['flavor_text_entries'],
                $pokemonSpecies,
                $this->versionService->getArrayVersions($language)
            );
        }
        $this->entityManager->flush();
    }
}