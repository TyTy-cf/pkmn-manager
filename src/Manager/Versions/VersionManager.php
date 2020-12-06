<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Versions\VersionGroupRepository;
use App\Repository\Versions\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionManager extends AbstractManager
{

    /**
     * @var VersionGroupRepository $versionGroupRepository
     */
    private VersionGroupRepository $versionGroupRepository;

    /**
     * @var VersionRepository
     */
    private VersionRepository $versionRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionRepository $versionRepository
     * @param VersionGroupRepository $versionGroupRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionRepository $versionRepository,
        VersionGroupRepository $versionGroupRepository
    )
    {
        $this->versionGroupRepository = $versionGroupRepository;
        $this->versionRepository = $versionRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Version|null
     * @throws NonUniqueResultException
     */
    private function getVersionByLanguageAndSlug(Language $language, string $slug): ?Version
    {
        return $this->versionRepository->getVersionByLanguageAndSlug($language, $slug);
    }

    /**
     * @param Language $language
     */
    public function getAllVersions(Language $language)
    {
        return $this->versionRepository->findBy([
           'language' => $language,
        ]);
    }

    /**
     * @param Language $language
     * @param $version
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $version)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(Version::class, $version['name']);

        if ($this->getVersionByLanguageAndSlug($language, $slug) === null)
        {
            // fetch the generation according to the group-version
            $urlDetailed = $this->apiManager->getDetailed($version['url'])->toArray();
            $versionGroup = $this->versionGroupRepository->getVersionGroupByLanguageAndName($language, $urlDetailed['version_group']['name']);
            $versionLang = $this->apiManager->getNameBasedOnLanguageFromArray($language->getCode(), $urlDetailed);

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