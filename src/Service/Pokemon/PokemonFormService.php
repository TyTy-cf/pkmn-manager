<?php


namespace App\Service\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonForm;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Pokemon\PokemonFormRepository;
use App\Repository\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonFormService extends AbstractService
{

    /**
     * @var PokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * @var PokemonFormRepository $pokemonFormRepository
     */
    private PokemonFormRepository $pokemonFormRepository;

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param VersionGroupService $versionGroupService
     * @param PokemonRepository $pokemonRepository
     * @param PokemonFormRepository $pokemonFormRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        VersionGroupService $versionGroupService,
        PokemonRepository $pokemonRepository,
        PokemonFormRepository $pokemonFormRepository
    ) {
        $this->pokemonRepository = $pokemonRepository;
        $this->versionGroupManager = $versionGroupService;
        $this->pokemonFormRepository = $pokemonFormRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return PokemonForm|object|null
     */
    private function getPokemonFormBySlug(string $slug)
    {
        return $this->pokemonFormRepository->findOneBySlug($slug);
    }

    /**
     * @param Pokemon $pokemon
     * @param Language $language
     * @throws TransportExceptionInterface
     */
    public function createPokemonFormFromPokemon(
        Pokemon $pokemon,
        Language $language
    ) {
        $pokemonFormDetailed = $this->apiService->getPokemonDetailedFromPokemon($pokemon)->toArray();

        foreach($pokemonFormDetailed['forms'] as $pokemonForm) {
            $urlDetailed = $this->apiService->apiConnect($pokemonForm['url'])->toArray();
            $slug = $this->textService->generateSlugFromClassWithLanguage(
                $language, PokemonForm::class, $urlDetailed['name']
            );
            $name = $this->apiService->getFieldContentFromLanguage(
                $language, $urlDetailed, 'names', 'name'
            );
            $formName = $this->apiService->getFieldContentFromLanguage(
                $language, $urlDetailed, 'form_names', 'name'
            );

            if ($this->getPokemonFormBySlug($slug) === null && $formName !== null) {
                $newPokemonForm = (new PokemonForm())
                    ->setSlug($slug)
                    ->setName($name)
                    ->setPokemon($pokemon)
                    ->setLanguage($language)
                    ->setFormName($formName)
                    ->setMega($urlDetailed['is_mega'])
                    ->setDefault($urlDetailed['is_default'])
                    ->setBattleOnly($urlDetailed['is_battle_only'])
                    ->setFormSprite($urlDetailed['sprites']['front_default'])
                ;
                // Change the name of the original pokemon
                if ($newPokemonForm->isDefault() && $name != null) {
                    $pokemon->setName($name);
                }

                $versionGroup = $this->versionGroupManager->getVersionGroupBySlug(
                    $language->getCode().'/version-group-'.$urlDetailed['version_group']['name']
                );
                $newPokemonForm->setVersionGroup($versionGroup);
                $this->entityManager->persist($newPokemonForm);
            }
        }
        $this->entityManager->flush();
    }

}
