<?php


namespace App\Service\Versions;


use App\Entity\Locations\Region;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Locations\RegionRepository;
use App\Repository\Versions\GenerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationService extends AbstractService
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
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param RegionRepository $regionRepo
     * @param GenerationRepository $generationRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        RegionRepository $regionRepo,
        GenerationRepository $generationRepository
    ) {
        $this->regionRepo = $regionRepo;
        $this->generationRepository = $generationRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $generation
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $generation)
    {
        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, Generation::class, $generation['name']
        );

        //Fetch URL details type
        $urlDetailed = $this->apiService->apiConnect($generation['url'])->toArray();
        if (null === $newGeneration = $this->$this->generationRepository->findOneBySlug($slug))
        {
            // Fetch name & description according the language
            $generationLang = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );
            $splittedGeneration = explode(' ', $generationLang);

            $region = null;
            if (null !== $urlDetailed['main_region']) {
                $slug = $this->textService->generateSlugFromClassWithLanguage(
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


















