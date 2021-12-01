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

/**
 * Class VersionService
 * @package App\Service\Versions
 *
 * @property VersionGroupRepository $versionGroupRepository
 * @property VersionRepository $versionRepository
 */
class VersionService extends AbstractService
{

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
        parent::__construct($entityManager, $apiService, $textService);
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
    public function getVersionsByLanguage(Language $language): array
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
        if (!in_array($version['name'], VersionGroup::$avoidList)) {
            //Check if the data exist in databases
            $slug = $this->textService->generateSlugFromClassWithLanguage(
                $language,
                Version::class,
                $version['name']
            );

            $newVersion = $this->getVersionBySlug($slug);
            if (null === $newVersion) {
                $newVersion = new Version();
            }

            // fetch the generation according to the group-version
            $urlDetailed = $this->apiService->apiConnect($version['url'])->toArray();

            $versionGroup = $this->versionGroupRepository->findOneBy([
                'apiName' => $urlDetailed['version_group']['name'],
                'language' => $language
            ]);
            $versionName = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );

            $newVersion
                ->setLogo('/images/versions/versions/' . $urlDetailed['name'] . '.png')
                ->setSlug($this->textService->slugify($versionName))
                ->setLanguage($language)
                ->setName($versionName)
                ->setVersionGroup($versionGroup)
            ;
            $this->entityManager->persist($newVersion);
        }
    }
}
