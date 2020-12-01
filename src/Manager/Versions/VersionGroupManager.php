<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Versions\GenerationRepository;
use App\Repository\Versions\VersionGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionGroupManager
{

    /**
     * @var VersionGroupRepository $versionGroupRepository
     */
    private VersionGroupRepository $versionGroupRepository;

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
     * @var GenerationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TextManager $textManager
     * @param VersionGroupRepository $versionGroupRepository
     * @param GenerationRepository $generationRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TextManager $textManager,
        VersionGroupRepository $versionGroupRepository,
        GenerationRepository $generationRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->versionGroupRepository = $versionGroupRepository;
        $this->generationRepository = $generationRepository;
    }

    /**
     * @param string $lang
     * @param $versionGroup
     * @throws NonUniqueResultException|TransportExceptionInterface
     */
    public function createVersionGroupIfNotExist(string $lang, $versionGroup)
    {
        //Fetch URL details type
        $urlVersionGroup = $versionGroup['url'];
        $urlDetailed = $this->apiManager->getDetailed($urlVersionGroup)->toArray();

        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(VersionGroup::class, $urlDetailed['name']);

        if (($newVersionGroup = $this->versionGroupRepository->getVersionGroupByLanguageAndSlug($language, $slug)) == null)
        {
            // fetch the generation according to the group-version
            $generationNumber = $this->apiManager->getIdFromUrl($urlDetailed['generation']['url']);
            $generation = $this->generationRepository->getGenerationByLanguageAndGenerationNumber($language, $generationNumber);

            $newVersionGroup = new VersionGroup();
            $newVersionGroup->setSlug($slug);
            $newVersionGroup->setLanguage($language);
            $newVersionGroup->setName($urlDetailed['name']);
            $newVersionGroup->setGeneration($generation);
            $this->entityManager->persist($newVersionGroup);
            $this->entityManager->flush();
        }
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return VersionGroup|null
     * @throws NonUniqueResultException
     */
    public function getVersionGroupByLanguageAndSlug(Language $language, string $slug): ?VersionGroup
    {
        return $this->versionGroupRepository->getVersionGroupByLanguageAndSlug(
            $language,
            $this->textManager->generateSlugFromClass(VersionGroup::class, $slug)
        );
    }

}