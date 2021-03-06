<?php


namespace App\Service\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Versions\GenerationRepository;
use App\Repository\Versions\VersionGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionGroupService extends AbstractService
{

    /**
     * @var GenerationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * @var VersionGroupRepository
     */
    private VersionGroupRepository $versionGroupRepository;

    /**
     * @var array $arrayVersionGroup
     */
    private static array $arrayVersionGroup;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param VersionGroupRepository $versionGroupRepository
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        VersionGroupRepository $versionGroupRepository,
        GenerationRepository $generationRepository
    ) {
        $this->generationRepository = $generationRepository;
        $this->versionGroupRepository = $versionGroupRepository;
        self::$arrayVersionGroup = array();
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param string $order
     * @return VersionGroup[]|array
     */
    public function getArrayVersionGroup(Language $language, string $order = 'ASC'): array
    {
        if (self::$arrayVersionGroup == null)
        {
            $tmpArrayVersionGroup= $this->getVersionGroupByLanguage($language, $order);
            foreach($tmpArrayVersionGroup as $versionGroup)
            {
                self::$arrayVersionGroup[$versionGroup->getSlug()] = $versionGroup;
            }
        }
        return self::$arrayVersionGroup;
    }

    /**
     * @param string $slug
     * @return VersionGroup|null
     */
    public function getVersionGroupBySlug(string $slug): ?VersionGroup
    {
        return $this->versionGroupRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param string $order
     * @return int|mixed|string
     */
    public function getVersionGroupByLanguage(Language $language, string $order)
    {
        return $this->versionGroupRepository->getVersionGroupByLanguage($language, $order);
    }

    /**
     * @param Language $language
     * @param $versionGroup
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $versionGroup)
    {
        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language,VersionGroup::class, $versionGroup['name']
        );

        if (null === $this->getVersionGroupBySlug($slug)
         && !in_array($versionGroup['name'], VersionGroup::$avoidList))
        {
            $urlDetailed = $this->apiService->apiConnect($versionGroup['url'])->toArray();

            // fetch the generation according to the group-version
            $generationNumber = $this->apiService->getIdFromUrl($urlDetailed['generation']['url']);
            $generation = $this->generationRepository->getGenerationByLanguageAndGenerationNumber(
                $language, $generationNumber
            );

            $newVersionGroup = (new VersionGroup())
                ->setSlug($slug)
                ->setLanguage($language)
                ->setName($urlDetailed['name'])
                ->setGeneration($generation)
                ->setDisplayedOrder($urlDetailed['order'])
            ;
            $this->entityManager->persist($newVersionGroup);
            $this->entityManager->flush();
        }
    }

}
