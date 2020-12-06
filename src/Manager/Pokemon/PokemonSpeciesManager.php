<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokedex\EggGroupManager;
use App\Manager\TextManager;
use App\Repository\Pokemon\PokemonSpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpeciesManager extends AbstractManager
{
    /**
     * @var PokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var PokemonSpeciesRepository
     */
    private PokemonSpeciesRepository $repository;

    /**
     * @var PokemonSpeciesVersionManager
     */
    private PokemonSpeciesVersionManager $pokemonSpeciesVersionManager;

    /**
     * @var EggGroupManager $eggGroupManager
     */
    private EggGroupManager $eggGroupManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param PokemonManager $pokemonManager
     * @param EggGroupManager $eggGroupManager
     * @param PokemonSpeciesVersionManager $pokemonSpeciesVersionManager
     * @param PokemonSpeciesRepository $repository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        PokemonManager $pokemonManager,
        EggGroupManager $eggGroupManager,
        PokemonSpeciesVersionManager $pokemonSpeciesVersionManager,
        PokemonSpeciesRepository $repository
    ) {
        $this->repository = $repository;
        $this->eggGroupManager = $eggGroupManager;
        $this->pokemonManager = $pokemonManager;
        $this->pokemonSpeciesVersionManager = $pokemonSpeciesVersionManager;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return PokemonSpecies|null
     */
    private function getPokemonSpeciesByLanguageAndSlug(Language $language, string $slug)
    {
        return $this->repository->findOneBy([
            'slug' => $language->getCode().'/'.$slug
        ]);
    }

    /**
     * Save a new pokemon from API to the database
     * Create his abilities and type(s) if necessary
     *
     * @param Language $language
     * @param $apiResponse
     * @return void
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClass(PokemonSpecies::class, $apiResponse['name']);

        if ($this->getPokemonSpeciesByLanguageAndSlug($language, $slug) === null)
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
                ->setHasGenderDifferences($urlDetailed['has_gender_differences']);

            // Set le evolve from species
            $pokemonSpecies->setEvolvesFromSpecies(
                $this->getPokemonSpeciesByLanguageAndSlug(
                    $language, $urlDetailed['name']
                )
            );

            // Set the egg(s) group
            foreach($urlDetailed['egg_groups'] as $eggGroupName)
            {
                $eggGroup = $this->eggGroupManager->getEggGroupBySlug(
                    $language, 'egg-group' . $eggGroupName['name']
                );
                if ($eggGroup !== null)
                {
                    $pokemonSpecies->addEggGroup($eggGroup);
                }
            }

            $this->entityManager->persist($pokemonSpecies);

            // Finally get the pokemon linked to this species and update it
            $pokemon = $this->pokemonManager->getPokemonByLanguageAndSlug(
                $language,
                $this->textManager->generateSlugFromClass(
                    Pokemon::class, $urlDetailed['varieties'][0]['pokemon']['name']
                )
            );
            $pokemon->setPokemonSpecies($pokemonSpecies);
            $this->entityManager->persist($pokemon);

            // Set PokemonSpeciesVersion
            


            $this->entityManager->flush();
        }
    }
}