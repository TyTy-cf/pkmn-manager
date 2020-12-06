<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\PokemonSpecies;
use App\Entity\Pokemon\PokemonSpeciesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Manager\TextManager;
use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use Doctrine\ORM\EntityManagerInterface;

class PokemonSpeciesVersionManager
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
     * @var TextManager $textManager
     */
    private TextManager $textManager;

    /**
     * PokemonSpeciesVersionManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param PokemonSpeciesVersionRepository $repository
     * @param TextManager $textManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        PokemonSpeciesVersionRepository $repository,
        TextManager $textManager
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->textManager = $textManager;
    }

    /**
     * @param Language $language
     * @param string $string
     * @return PokemonSpeciesVersion|null
     */
    private function getPokemonSpeciesVersionBySlug(Language $language, string $string)
    {
        return $this->repository->findOneBy([
            'slug' => $language->getCode().'/'.$string
        ]);
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
        foreach($urlDetailed as $flavorTextDetail)
        {
            if ($flavorTextDetail['language']['name'] === $language->getCode())
            {
                /** @var Version $version */
                $version = $arrayVersions['version-'.$flavorTextDetail['version']['name']];
                $slug = $pokemonSpecies->getSlug().'-'.$version->getSlug();
                if ($this->getPokemonSpeciesVersionBySlug($language, $slug) === null) {
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