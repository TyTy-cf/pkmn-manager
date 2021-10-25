<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSpritesVersion;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Repository\Pokemon\PokemonRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Users\LanguageService;
use App\Service\Versions\VersionGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class PokemonSpritesVersionService
 * @package App\Service\Pokemon
 *
 * @property PokemonRepository $pokemonRepository
 * @property VersionGroupService $versionGroupService
 * @property PokemonSpritesService $pokemonSpritesService
 */
class PokemonSpritesVersionService extends AbstractService
{

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param VersionGroupService $versionGroupService
     * @param PokemonSpritesService $pokemonSpritesService
     * @param TextService $textService
     * @param PokemonRepository $pokemonRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        VersionGroupService $versionGroupService,
        PokemonSpritesService $pokemonSpritesService,
        TextService $textService,
        PokemonRepository $pokemonRepository
    )
    {
        $this->pokemonRepository = $pokemonRepository;
        $this->pokemonSpritesService = $pokemonSpritesService;
        $this->versionGroupService = $versionGroupService;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
         // Create the sprite Entity
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        $pokemon = $this->pokemonRepository->findOneBy(['idApi' => $urlDetailed['id'], 'language' => $language]);

        if ($pokemon !== null) {
            // Update default sprites
            $this->pokemonSpritesService->createPokemonSprites(
                $this->entityManager,
                $pokemon,
                $this->textService,
                $urlDetailed['sprites']
            );

            foreach($urlDetailed['sprites']['versions'] as $keyGeneration => $version)
            {
                // Iterate over each content of generation-i
                foreach($version as $key => $spriteVersion)
                {
                    $slugKey = $this->textService->generateSlugFromClassWithLanguage(
                        $language,VersionGroup::class, $key
                    );
                    $versionGroup = $this->versionGroupService->getVersionGroupBySlug($slugKey);
                    if ($versionGroup != null)
                    {
                        $frontDefault = isset($spriteVersion['front_default']) ? $spriteVersion['front_default'] : null;
                        $frontShiny = isset($spriteVersion['front_shiny']) ? $spriteVersion['front_shiny'] : null;
                        $frontFemale = isset($spriteVersion['front_female']) ? $spriteVersion['front_female'] : null;
                        $frontFemaleShiny = isset($spriteVersion['front_shiny_female']) ? $spriteVersion['front_shiny_female'] : null;
                        // if at least one of the default sprites key are set we can create the object
                        if ($frontDefault != null || $frontShiny != null || $frontFemale != null || $frontFemaleShiny != null)
                        {
                            $pokemonSpritesVersion = (new PokemonSpritesVersion())
                                ->setPokemon($pokemon)
                                ->setVersionGroup($versionGroup)
                                ->setUrlDefault($frontDefault)
                                ->setUrlDefaultShiny($frontShiny)
                                ->setUrlDefaultFemale($frontFemale)
                                ->setUrlDefaultFemaleShiny($frontFemaleShiny)
                            ;
                            $this->entityManager->persist($pokemonSpritesVersion);
                        }
                    }
                }
            }
        }
    }

}
