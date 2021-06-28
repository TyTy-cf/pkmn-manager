<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Service\TextService;
use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class PokemonSpeciesVersionService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PokemonSpeciesVersionRepository
     */
    private PokemonSpeciesVersionRepository $repository;

    /**
     * @var TextService $textManager
     */
    private TextService $textManager;

    /**
     * PokemonSpeciesVersionManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonSpeciesVersionRepository $repository
     * @param TextService $textManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonSpeciesVersionRepository $repository,
        TextService $textManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->textManager = $textManager;
    }

    /**
     * @param string $slug
     * @return PokemonSpeciesVersion|null
     */
    private function getPokemonSpeciesVersionBySlug(string $slug)
    {
        return $this->repository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $urlDetailed
     * @param PokemonSpecies $pokemonSpecies
     * @param array $arrayVersions
     */
    public function createFromApi
    (
        Language $language,
        $urlDetailed,
        PokemonSpecies $pokemonSpecies,
        array $arrayVersions
    )
    {
        $codeLang = $language->getCode();
        foreach($urlDetailed as $flavorTextDetail)
        {
            if ($flavorTextDetail['language']['name'] === $codeLang)
            {
                /** @var Version $version */
                $version = $arrayVersions[$codeLang.'/version-'.$flavorTextDetail['version']['name']];
                $slug = $pokemonSpecies->getSlug().'-'.$version->getSlug();
                if ($this->getPokemonSpeciesVersionBySlug($slug) === null) {
                    $pokemonSpeciesVersion = (new PokemonSpeciesVersion())
                        ->setPokemonSpecies($pokemonSpecies)
                        ->setVersion($version)
                        ->setLanguage($language)
                        ->setSlug($slug)
                        ->setDescription(
                            $this->textManager->removeReturnLineFromText($flavorTextDetail['flavor_text'])
                        );
                    $this->entityManager->persist($pokemonSpeciesVersion);
                }
            }
        }
    }
}
