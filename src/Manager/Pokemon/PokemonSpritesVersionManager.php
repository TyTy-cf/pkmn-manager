<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpritesVersion;
use App\Entity\Versions\VersionGroup;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\VersionGroupManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpritesVersionManager
{
    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var LanguageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var TextManager
     */
    private TextManager $textManager;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param LanguageManager $languageManager
     * @param TextManager $textManager
     * @param VersionGroupManager $versionGroupManager
     * @param PokemonManager $pokemonManager
     */
    public function __construct(
        LanguageManager $languageManager,
        EntityManagerInterface $entityManager,
        VersionGroupManager $versionGroupManager,
        TextManager $textManager,
        PokemonManager $pokemonManager
    ) {
        $this->entityManager = $entityManager;
        $this->pokemonManager = $pokemonManager;
        $this->languageManager = $languageManager;
        $this->textManager = $textManager;
        $this->versionGroupManager = $versionGroupManager;
    }

    /**
     * @param string $lang
     * @param array $arrayVersions
     * @param string $name
     * @throws NonUniqueResultException|TransportExceptionInterface
     */
    public function saveSpritesFromApi(string $lang, array $arrayVersions, string $name)
    {
        $language = $this->languageManager->createLanguage($lang);
        // Create the sprite Entity
        foreach($arrayVersions as $keyGeneration => $version)
        {
            // Iterate over each content of generation-i
            foreach($version as $key => $spriteVersion)
            {
                $slugVersion = $this->textManager->generateSlugFromClass(VersionGroup::class, $key);
                $versionGroup = $this->versionGroupManager->getVersionGroupByLanguageAndSlug($language, $slugVersion);
                if ($versionGroup != null)
                {
                    $frontDefault = isset($spriteVersion['front_default']) ? $spriteVersion['front_default'] : null;
                    $frontShiny = isset($spriteVersion['front_shiny']) ? $spriteVersion['front_shiny'] : null;
                    $frontFemale = isset($spriteVersion['front_female']) ? $spriteVersion['front_female'] : null;
                    $frontFemaleShiny = isset($spriteVersion['front_shiny_female']) ? $spriteVersion['front_shiny_female'] : null;
                    // if at least one of the default sprites key are set we can create the object
                    if ($frontDefault != null || $frontShiny != null || $frontFemale != null || $frontFemaleShiny != null)
                    {
                        $slug = $this->textManager->generateSlugFromClass(Pokemon::class, $name);
                        $pokemon = $this->pokemonManager->getPokemonByLanguageAndSlug($lang, $slug);
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