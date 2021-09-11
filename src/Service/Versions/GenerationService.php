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

/**
 * Class GenerationService
 * @package App\Service\Versions
 *
 * @property GenerationRepository $generationRepository
 * @property RegionRepository $regionRepo
 */
class GenerationService extends AbstractService
{

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

        if (null === $newGeneration = $this->generationRepository->findOneBySlug($slug)) {
            $newGeneration = (new Generation());
        }
        //Fetch URL details type
        $urlDetailed = $this->apiService->apiConnect($generation['url'])->toArray();
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
        $urlApiId = $urlDetailed['id'];
        $nameGeneration = $splittedGeneration[0] . ' ' . $urlApiId;
        $slug = $this->textService->slugify($language->getCode() . '-' . $generation['name']);

        $newGeneration
            ->setSlug($slug)
            ->setCode(Generation::$relationArray[$urlApiId])
            ->setLanguage($language)
            ->setNumber($urlApiId)
            ->setName($nameGeneration)
            ->setMainRegion($region)
        ;
        $this->entityManager->persist($newGeneration);
    }

}


















