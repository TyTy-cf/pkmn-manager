<?php


namespace App\Service\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Versions\VersionGroupRepository;
use App\Repository\Versions\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionService extends AbstractService
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
     * @var array $arrayVersions
     */
    private static array $arrayVersions;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param VersionRepository $versionRepository
     * @param VersionGroupRepository $versionGroupRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        VersionRepository $versionRepository,
        VersionGroupRepository $versionGroupRepository
    )
    {
        $this->versionGroupRepository = $versionGroupRepository;
        $this->versionRepository = $versionRepository;
        self::$arrayVersions = array();
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @return Version[]|array
     */
    public function getArrayVersions(Language $language): array
    {
        if (self::$arrayVersions == null)
        {
            $tmpArrayVersions = $this->getAllVersions($language);
            foreach($tmpArrayVersions as $version)
            {
                self::$arrayVersions[$version->getSlug()] = $version;
            }
        }
        return self::$arrayVersions;
    }

    /**
     * @param string $slug
     * @return Version|null
     */
    private function getVersionBySlug(string $slug): ?Version
    {
        return $this->versionRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @return array
     */
    public function getAllVersions(Language $language): array
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
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language,
            Version::class,
            $version['name']
        );

        if (null === $this->getVersionBySlug($slug) && !in_array($version['name'], VersionGroup::$avoidList))
        {
            // fetch the generation according to the group-version
            $urlDetailed = $this->apiService->apiConnect($version['url'])->toArray();
            $versionGroup = $this->versionGroupRepository->getVersionGroupByLanguageAndName(
                $language, $urlDetailed['version_group']['name']
            );
            $versionLang = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );

            $newVersion = new Version();
            if (empty($versionLang)) {
                $versionLang = ucfirst($urlDetailed['name']);
            }
            $newVersion->setSlug($slug)
                ->setLanguage($language)
                ->setName($versionLang)
                ->setVersionGroup($versionGroup)
            ;
            $this->entityManager->persist($newVersion);
            $this->entityManager->flush();
        }
    }
}