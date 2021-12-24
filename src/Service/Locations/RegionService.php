<?php


namespace App\Service\Locations;


use App\Entity\Locations\Location;
use App\Entity\Locations\LocationArea;
use App\Entity\Locations\Region;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Locations\LocationRepository;
use App\Repository\Locations\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class RegionService
 * @package App\Service\Locations
 *
 * @property RegionRepository $regionRepo
 * @property LocationRepository $locationRepo
 * @property LocationService $locationService
 */
class RegionService extends AbstractService
{

    /**
     * RegionService constructor.
     * @param RegionRepository $regionRepo
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityService
     * @param ApiService $apiService
     * @param TextService $textService
     * @param LocationService $locationService
     */
    public function __construct(
        RegionRepository $regionRepo,
        LocationRepository $locationRepo,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService,
        LocationService $locationService
    ) {
        $this->regionRepo = $regionRepo;
        $this->locationRepo = $locationRepo;
        $this->locationService = $locationService;
        parent::__construct($entityService, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlRegion = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        // Get the name by language
        $name = $this->apiService->getNameBasedOnLanguageFromArray(
            $language->getCode(), $urlRegion
        );
        // Check if the name exist - if yes, we can create it
        if ($name !== null) {
            $slug = $this->textService->slugify($name);
            if (null === $region = $this->regionRepo->findOneBySlug($slug)) {
                // Create the region first
                $region = new Region();
            }
            $region
                ->setName($name)
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($region);

            // Create the location for the region
            $this->locationService->createLocationFromUrl($language, $urlRegion, $region);
        }
        $this->entityManager->flush();
    }

}
