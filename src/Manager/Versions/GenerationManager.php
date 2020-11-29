<?php


namespace App\Manager\Versions;


use App\Entity\Versions\Generation;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationManager
{

    /**
     * @var GenerationRepository $generationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TextManager $textManager
     */
    private TextManager $textManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;


    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TextManager $textManager
     * @param GenerationRepository $generationRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TextManager $textManager,
        GenerationRepository $generationRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->generationRepository = $generationRepository;
    }

    /**
     * @param string $lang
     * @param $generation
     * @throws TransportExceptionInterface|NonUniqueResultException
     */
    public function createGenerationIfNotExist(string $lang, $generation)
    {
        //Fetch URL details type
        $urlGeneration = $generation['url'];
        $urlDetailed = $this->apiManager->getDetailed($urlGeneration)->toArray();

        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(Generation::class, $urlDetailed['name']);

        if (($newAbility = $this->generationRepository->getGenerationByLanguageAndSlug($language, $slug)) == null)
        {
            // Fetch name & description according the language
            $generationLang = $this->apiManager->getNameBasedOnLanguageFromArray($lang, $urlDetailed['names']);
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


















