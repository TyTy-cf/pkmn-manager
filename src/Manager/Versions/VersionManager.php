<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Versions\VersionGroupRepository;
use App\Repository\Versions\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionManager
{

    /**
     * @var VersionRepository $versionRepository
     */
    private VersionRepository $versionRepository;

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
     * @var VersionGroupRepository $versionGroupRepository
     */
    private VersionGroupRepository $versionGroupRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TextManager $textManager
     * @param VersionRepository $versionRepository
     * @param VersionGroupRepository $versionGroupRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TextManager $textManager,
        VersionRepository $versionRepository,
        VersionGroupRepository $versionGroupRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->versionGroupRepository = $versionGroupRepository;
        $this->versionRepository = $versionRepository;
    }

    /**
     * @param string $lang
     * @param $version
     * @throws NonUniqueResultException|TransportExceptionInterface
     */
    public function createVersionIfNotExist(string $lang, $version)
    {
        //Fetch URL details type
        $urlVersion = $version['url'];
        $urlDetailed = $this->apiManager->getDetailed($urlVersion)->toArray();

        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(Version::class, $urlDetailed['name']);

        if (($newVersion = $this->versionRepository->getVersionByLanguageAndSlug($language, $slug)) == null)
        {
            // fetch the generation according to the group-version
            $versionGroup = $this->versionGroupRepository->getVersionGroupByLanguageAndName($language, $urlDetailed['version_group']['name']);
            $versionLang = $this->apiManager->getNameBasedOnLanguageFromArray($lang, $urlDetailed['names']);

            $newVersion = new Version();
            $newVersion->setSlug($slug);
            $newVersion->setLanguage($language);
            if (empty($versionLang)) {
                $versionLang = ucfirst($urlDetailed['name']);
            }
            $newVersion->setName($versionLang);
            $newVersion->setVersionGroup($versionGroup);
            $this->entityManager->persist($newVersion);
            $this->entityManager->flush();
        }
    }
}