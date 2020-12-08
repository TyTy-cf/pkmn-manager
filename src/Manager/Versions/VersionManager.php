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
     * @var array $arrayVersions
     */
    private static array $arrayVersions;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionRepository $versionRepository
     * @param VersionGroupRepository $versionGroupRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionRepository $versionRepository,
        VersionGroupRepository $versionGroupRepository
    )
    {
        $this->versionGroupRepository = $versionGroupRepository;
        $this->versionRepository = $versionRepository;
        self::$arrayVersions = array();
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @return Version[]|array
     */
    public function getArrayVersions(Language $language)
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
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language,
            Version::class,
            $version['name']
        );

        if ($this->getVersionBySlug($slug) === null)
        {
            // fetch the generation according to the group-version
            $urlDetailed = $this->apiManager->getDetailed($version['url'])->toArray();
            $versionGroup = $this->versionGroupRepository->getVersionGroupByLanguageAndName(
                $language, $urlDetailed['version_group']['name']
            );
            $versionLang = $this->apiManager->getNameBasedOnLanguageFromArray(
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