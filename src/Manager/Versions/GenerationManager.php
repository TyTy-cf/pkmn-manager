<?php


namespace App\Manager\Versions;


use App\Entity\Locations\Region;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Location\RegionRepository;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationManager extends AbstractManager
{
    /**
     * @var GenerationRepository
     */
    private GenerationRepository $generationRepository;

    /**
     * @var RegionRepository $regionRepo
     */
    private RegionRepository $regionRepo;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param RegionRepository $regionRepo
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        RegionRepository $regionRepo,
        GenerationRepository $generationRepository
    )
    {
        $this->regionRepo = $regionRepo;
        $this->generationRepository = $generationRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Generation|null
     */
    public function getGenerationBySlug(string $slug): object
    {
        return $this->generationRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllGenerationsByLanguage(Language $language)
    {
        return $this->generationRepository->getGenerationByLanguage($language);
    }

    /**
     * @param Language $language
     * @param $generation
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $generation)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Generation::class, $generation['name']
        );

        //Fetch URL details type
        $urlDetailed = $this->apiManager->getDetailed($generation['url'])->toArray();
        if (($newGeneration = $this->getGenerationBySlug($slug)) === null)
        {
            // Fetch name & description according the language
            $generationLang = $this->apiManager->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );
            $splittedGeneration = explode(' ', $generationLang);

            $region = null;
            if ($urlDetailed['main_region'] !== null) {
                $slug = $this->textManager->generateSlugFromClassWithLanguage(
                    $language, Region::class, $urlDetailed['main_region']['name']
                );
                $region = $this->regionRepo->findOneBySlug($slug);
            }
            $newGeneration = (new Generation())
                ->setSlug($slug)
                ->setCode(Generation::$relationArray[$urlDetailed['id']])
                ->setLanguage($language)
                ->setNumber($urlDetailed['id'])
                ->setName($splittedGeneration[0] . ' ' . $urlDetailed['id'])
                ->setMainRegion($region)
            ;
            $this->entityManager->persist($newGeneration);
        }
        $this->entityManager->flush();
    }

}


















