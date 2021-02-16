<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpritesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Users\LanguageService;
use App\Service\Versions\VersionGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpritesVersionService extends AbstractService
{
    /**
     * @var PokemonService $pokemonManager
     */
    private PokemonService $pokemonManager;

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * @var PokemonSpritesManager $pokemonSpritesManager
     */
    private PokemonSpritesManager $pokemonSpritesManager;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param VersionGroupService $versionGroupService
     * @param PokemonSpritesManager $pokemonSpritesService
     * @param TextService $textManager
     * @param PokemonService $pokemonManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        VersionGroupService $versionGroupService,
        PokemonSpritesManager $pokemonSpritesService,
        TextService $textManager,
        PokemonService $pokemonManager
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->pokemonSpritesManager = $pokemonSpritesService;
        $this->versionGroupManager = $versionGroupService;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
         // Create the sprite Entity
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();
        foreach($urlDetailed['sprites']['versions'] as $keyGeneration => $version)
        {
            // Iterate over each content of generation-i
            foreach($version as $key => $spriteVersion)
            {
                $slugKey = $this->textManager->generateSlugFromClassWithLanguage(
                    $language,VersionGroup::class, $key
                );
                $versionGroup = $this->versionGroupManager->getVersionGroupBySlug($slugKey);
                if ($versionGroup != null)
                {
                    $frontDefault = isset($spriteVersion['front_default']) ? $spriteVersion['front_default'] : null;
                    $frontShiny = isset($spriteVersion['front_shiny']) ? $spriteVersion['front_shiny'] : null;
                    $frontFemale = isset($spriteVersion['front_female']) ? $spriteVersion['front_female'] : null;
                    $frontFemaleShiny = isset($spriteVersion['front_shiny_female']) ? $spriteVersion['front_shiny_female'] : null;
                    // if at least one of the default sprites key are set we can create the object
                    if ($frontDefault != null || $frontShiny != null || $frontFemale != null || $frontFemaleShiny != null)
                    {
                        $slug = $this->textManager->generateSlugFromClassWithLanguage(
                            $language, Pokemon::class, $urlDetailed['name']
                        );
                        $pokemon = $this->pokemonManager->getPokemonBySlug($slug);

                        $pokemonSpritesVersion = (new PokemonSpritesVersion())
                            ->setPokemon($pokemon)
                            ->setVersionGroup($versionGroup)
                            ->setUrlDefault($frontDefault)
                            ->setUrlDefaultShiny($frontShiny)
                            ->setUrlDefaultFemale($frontFemale)
                            ->setUrlDefaultFemaleShiny($frontFemaleShiny)
                        ;
                        $this->entityManager->persist($pokemonSpritesVersion);
                        // Update default sprites
                        $this->pokemonSpritesManager->createPokemonSprites(
                            $this->entityManager,
                            $pokemon,
                            $this->textManager,
                            $urlDetailed['sprites']
                        );
                    }
                }
            }
        }
        $this->entityManager->flush();
    }

}