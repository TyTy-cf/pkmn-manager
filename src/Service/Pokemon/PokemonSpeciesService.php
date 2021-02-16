<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokedex\EggGroupService;
use App\Service\TextManager;
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
    private PokemonService $pokemonManager;

    /**
     * @var PokemonSpeciesRepository
     */
    private PokemonSpeciesRepository $repository;

    /**
     * @var PokemonSpeciesVersionManager
     */
    private PokemonSpeciesVersionManager $pokemonSpeciesVersionManager;

    /**
     * @var EggGroupService $eggGroupManager
     */
    private EggGroupService $eggGroupManager;

    /**
     * @var VersionService $versionManager
     */
    private VersionService $versionManager;

    /**
     * @var GenerationService $generationManager
     */
    private GenerationService $generationManager;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextManager $textManager
     * @param PokemonService $pokemonManager
     * @param EggGroupService $eggGroupManager
     * @param VersionService $versionManager
     * @param GenerationService $generationManager
     * @param PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     * @param PokemonSpeciesRepository $repository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextManager $textManager,
        PokemonService $pokemonManager,
        EggGroupService $eggGroupManager,
        VersionService $versionManager,
        GenerationService $generationManager,
        PokemonSpeciesVersionManager $pokemonSpeciesVersionManager,
        PokemonSpeciesRepository $repository
    ) {
        $this->repository = $repository;
        $this->eggGroupManager = $eggGroupManager;
        $this->generationManager = $generationManager;
        $this->pokemonManager = $pokemonManager;
        $this->versionManager = $versionManager;
        $this->pokemonSpeciesVersionManager = $pokemonSpeciesVersionManager;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return PokemonSpecies|null
     */
    public function getPokemonSpeciesBySlug(string $slug)
    {
        return $this->repository->findOneBySlug($slug);
    }

    /**
     * @param string $slug
     * @return PokemonSpecies|null
     * @throws NonUniqueResultException
     */
    public function getSimplePokemonSpeciesBySlug(string $slug)
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
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, PokemonSpecies::class, $apiResponse['name']
        );

        if (($pokemonSpecies = $this->getPokemonSpeciesBySlug($slug)) === null)
        {
            $codeLang = $language->getCode();
            $pokemonSpeciesName = $this->apiManager->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );
            $genera = $this->apiManager->getFieldContentFromLanguage(
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

            if (count($urlDetailed['evolves_from_species']) > 0)
            {
                // Set le evolve from species
                $pokemonSpecies->setEvolvesFromSpecies(
                    $this->getPokemonSpeciesBySlug(
                        $this->textManager->generateSlugFromClassWithLanguage(
                            $language, PokemonSpecies::class, $urlDetailed['evolves_from_species']['name']
                        )
                    )
                );
            }

            // Set the egg(s) group
            foreach($urlDetailed['egg_groups'] as $eggGroupName)
            {
                $eggGroup = $this->eggGroupManager->getEggGroupBySlug(
                    $language->getCode().'/egg-group-' . $eggGroupName['name']
                );
                if ($eggGroup !== null)
                {
                    $pokemonSpecies->addEggGroup($eggGroup);
                }
            }

            $this->entityManager->persist($pokemonSpecies);

            // Finally get the pokemon linked to this species and update it
            $pokemon = $this->pokemonManager->getPokemonBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language,Pokemon::class, $urlDetailed['varieties'][0]['pokemon']['name']
                )
            );

            $pokemon->setPokemonSpecies($pokemonSpecies);
            $this->entityManager->persist($pokemon);

            // Set PokemonSpeciesVersion
            $this->pokemonSpeciesVersionManager->createFromApi(
                $language,
                $urlDetailed['flavor_text_entries'],
                $pokemonSpecies,
                $this->versionManager->getArrayVersions($language)
            );
        }
        $this->entityManager->flush();
    }
}