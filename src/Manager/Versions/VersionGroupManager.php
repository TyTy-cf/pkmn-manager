<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Versions\GenerationRepository;
use App\Repository\Versions\VersionGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionGroupManager extends AbstractManager
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
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionGroupRepository $versionGroupRepository
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionGroupRepository $versionGroupRepository,
        GenerationRepository $generationRepository
    )
    {
        $this->generationRepository = $generationRepository;
        $this->versionGroupRepository = $versionGroupRepository;
        self::$arrayVersionGroup = array();
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @return VersionGroup[]|array
     */
    public function getArrayVersionGroup(Language $language)
    {
        if (self::$arrayVersionGroup == null)
        {
            $tmpArrayVersionGroup= $this->getAllVersionGroupByLanguage($language);
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
     * @param Generation $generation
     * @param Language $language
     * @return int|mixed|string
     */
    public function getVersionGroupByGenerationAndLanguage(Generation $generation, Language $language)
    {
        return $this->versionGroupRepository->getVersionGroupByGenerationAndLanguage($generation, $language);
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getVersionGroupByLanguage(Language $language)
    {
        return $this->versionGroupRepository->getVersionGroupByLanguage($language);
    }

    /**
     * @param Language $language
     * @return VersionGroup[]
     */
    private function getAllVersionGroupByLanguage(Language $language)
    {
        return $this->versionGroupRepository->findBy([
            'language' => $language,
        ]);
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
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language,VersionGroup::class, $versionGroup['name']
        );

        if ($this->getVersionGroupBySlug($slug) === null)
        {
            $urlDetailed = $this->apiManager->getDetailed($versionGroup['url'])->toArray();

            // fetch the generation according to the group-version
            $generationNumber = $this->apiManager->getIdFromUrl($urlDetailed['generation']['url']);
            $generation = $this->generationRepository->getGenerationByLanguageAndGenerationNumber(
                $language, $generationNumber
            );

            $newVersionGroup = (new VersionGroup())
                ->setSlug($slug)
                ->setLanguage($language)
                ->setName($urlDetailed['name'])
                ->setGeneration($generation)
                ->setOrder($urlDetailed['order'])
            ;
            $this->entityManager->persist($newVersionGroup);
            $this->entityManager->flush();
        }
    }

}