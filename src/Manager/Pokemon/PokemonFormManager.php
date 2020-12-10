<?php


namespace App\Manager\Pokemon;


use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonForm;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Pokemon\PokemonFormRepository;
use App\Repository\Pokemon\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonFormManager extends AbstractManager
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
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param PokemonRepository $pokemonRepository
     * @param PokemonFormRepository $pokemonFormRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        PokemonRepository $pokemonRepository,
        PokemonFormRepository $pokemonFormRepository
    ) {
        $this->pokemonRepository = $pokemonRepository;
        $this->pokemonFormRepository = $pokemonFormRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
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
        $pokemonFormDetailed = $this->apiManager->getPokemonDetailedFromPokemon($pokemon)->toArray();

        foreach($pokemonFormDetailed['forms'] as $pokemonForm) {
            $urlDetailed = $this->apiManager->getDetailed($pokemonForm['url'])->toArray();
            $slug = $this->textManager->generateSlugFromClassWithLanguage(
                $language, PokemonForm::class, $urlDetailed['name']
            );
            $name = $this->apiManager->getFieldContentFromLanguage(
                $language, $urlDetailed, 'names', 'name'
            );
            $formName = $this->apiManager->getFieldContentFromLanguage(
                $language, $urlDetailed, 'form_names', 'name'
            );

            if ($this->getPokemonFormBySlug($slug) === null && $formName !== null) {
                $pokemonForm = (new PokemonForm())
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
                $this->entityManager->persist($pokemonForm);
                // Change the name of the original pokemon
                if ($pokemonForm->isDefault() && $name != null) {
                    $pokemon->setName($name);
                }
            }
        }
        $this->entityManager->flush();
    }

}
