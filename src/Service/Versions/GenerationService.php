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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $generation)
    {
        $urlDetailed = $this->apiService->apiConnect($generation['url'])->toArray();
        $urlApiId = $urlDetailed['id'];
        $codeLanguage = $language->getCode();
        $generationLang = $this->apiService->getNameBasedOnLanguageFromArray($codeLanguage, $urlDetailed);
        $splittedGeneration = explode(' ', $generationLang);
        $nameGeneration = (empty($splittedGeneration[0]) ? 'Generation' : $splittedGeneration[0]) . ' ' . $urlApiId;
        $slug = $this->textService->slugify($nameGeneration);

        $isNew = false;
        if (null === $newGeneration = $this->generationRepository->findOneBySlugAndLanguage($slug, $codeLanguage)) {
            $newGeneration = (new Generation());
        }

        $region = null;
        if (null !== $urlDetailed['main_region']) {
            $slug = $this->textService->generateSlugFromClassWithLanguage(
                $language, Region::class, $urlDetailed['main_region']['name']
            );
            $region = $this->regionRepo->findOneBySlug($slug);
        }

        $newGeneration
            ->setSlug($slug)
            ->setCode(Generation::$relationArray[$urlApiId])
            ->setLanguage($language)
            ->setNumber($urlApiId)
            ->setName($nameGeneration)
            ->setMainRegion($region)
        ;
        if ($isNew) {
            $this->entityManager->persist($newGeneration);
        }
    }

}


















