<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationManager extends AbstractManager
{
    /**
     * @var GenerationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        GenerationRepository $generationRepository
    )
    {
        $this->generationRepository = $generationRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Generation|null
     * @throws NonUniqueResultException
     */
    public function getGenerationByLanguageAndSlug(Language $language, string $slug): ?Generation
    {
        return $this->generationRepository->getGenerationByLanguageAndSlug($language, $slug);
    }

    /**
     * @param Language $language
     * @param $generation
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $generation)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(Generation::class, $generation['name']);

        if ($this->getGenerationByLanguageAndSlug($language, $slug) === null)
        {
            //Fetch URL details type
            $urlDetailed = $this->apiManager->getDetailed($generation['url'])->toArray();

            if (($newAbility = $this->getGenerationByLanguageAndSlug($language, $slug)) == null)
            {
                // Fetch name & description according the language
                $generationLang = $this->apiManager->getNameBasedOnLanguageFromArray($language->getCode(), $urlDetailed);
                $splittedGeneration = explode(' ', $generationLang);

                $newGeneration = new Generation();
                $newGeneration->setSlug($slug);
                $newGeneration->setCode(Generation::$relationArray[$urlDetailed['id']]);
                $newGeneration->setLanguage($language);
                $newGeneration->setNumber($urlDetailed['id']);
                $newGeneration->setName($splittedGeneration[0] . ' ' . $urlDetailed['id']);
                $this->entityManager->persist($newGeneration);
                $this->entityManager->flush();
            }
        }
    }

}


















