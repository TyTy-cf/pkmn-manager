<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpritesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\VersionGroupManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpritesVersionManager extends AbstractManager
{
    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * PokemonManager constructor.
     *
     * @param LanguageManager $languageManager
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param VersionGroupManager $versionGroupManager
     * @param TextManager $textManager
     * @param PokemonManager $pokemonManager
     */
    public function __construct
    (
        LanguageManager $languageManager,
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        VersionGroupManager $versionGroupManager,
        TextManager $textManager,
        PokemonManager $pokemonManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
         // Create the sprite Entity
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        foreach($urlDetailed['sprites']['versions'] as $keyGeneration => $version)
        {
            // Iterate over each content of generation-i
            foreach($version as $key => $spriteVersion)
            {
                $slugKey = $this->textManager->generateSlugFromClass(VersionGroup::class, $key);
                $versionGroup = $this->versionGroupManager->getVersionGroupByLanguageAndSlug($language, $slugKey);
                if ($versionGroup != null)
                {
                    $frontDefault = isset($spriteVersion['front_default']) ? $spriteVersion['front_default'] : null;
                    $frontShiny = isset($spriteVersion['front_shiny']) ? $spriteVersion['front_shiny'] : null;
                    $frontFemale = isset($spriteVersion['front_female']) ? $spriteVersion['front_female'] : null;
                    $frontFemaleShiny = isset($spriteVersion['front_shiny_female']) ? $spriteVersion['front_shiny_female'] : null;
                    // if at least one of the default sprites key are set we can create the object
                    if ($frontDefault != null || $frontShiny != null || $frontFemale != null || $frontFemaleShiny != null)
                    {
                        $slug = $this->textManager->generateSlugFromClass(Pokemon::class, $urlDetailed['name']);
                        $pokemon = $this->pokemonManager->getPokemonByLanguageAndSlug($language, $slug);
                        $pokemonSpritesVersion = new PokemonSpritesVersion();
                        $pokemonSpritesVersion->setPokemon($pokemon);
                        $pokemonSpritesVersion->setVersionGroup($versionGroup);
                        $pokemonSpritesVersion->setUrlDefault($frontDefault);
                        $pokemonSpritesVersion->setUrlDefaultShiny($frontShiny);
                        $pokemonSpritesVersion->setUrlDefaultFemale($frontFemale);
                        $pokemonSpritesVersion->setUrlDefaultFemaleShiny($frontFemaleShiny);
                        $this->entityManager->persist($pokemonSpritesVersion);
                    }
                }
            }
        }
        $this->entityManager->flush();
    }

}