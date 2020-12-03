<?php


namespace App\Manager\Versions;


use App\Entity\Users\Language;
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
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return VersionGroup|null
     * @throws NonUniqueResultException
     */
    public function getVersionGroupByLanguageAndSlug(Language $language, string $slug): ?VersionGroup
    {
        return $this->versionGroupRepository->getVersionGroupByLanguageAndSlug($language, $slug);
    }

    /**
     * @param int $generation
     * @param string $name
     * @return VersionGroup|null
     */
    public function getVersionGroupFromGenerationIdAndName(int $generation, string $name)
    {
        return $this->versionGroupRepository->findOneBy([
            'generation' => $generation,
            'name' => $name
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
        $slug = $this->textManager->generateSlugFromClass(VersionGroup::class, $versionGroup['name']);

        if (($newVersionGroup = $this->getVersionGroupByLanguageAndSlug($language, $slug)) == null)
        {
            $urlDetailed = $this->apiManager->getDetailed($versionGroup['url'])->toArray();

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

}