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

class RegionService extends AbstractService
{
    /**
     * @var RegionRepository $regionRepo
     */
    private RegionRepository $regionRepo;

    /**
     * @var LocationRepository $locationRepo
     */
    private LocationRepository $locationRepo;

    /**
     * RegionService constructor.
     * @param RegionRepository $regionRepo
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityService
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct(
        RegionRepository $regionRepo,
        LocationRepository $locationRepo,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->regionRepo = $regionRepo;
        $this->locationRepo = $locationRepo;
        parent::__construct($entityService, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, Region::class, $apiResponse['name']
        );
        //Fetch URL details type
        $urlRegion = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        if (null === $region = $this->regionRepo->findOneBySlug($slug))
        {
            // Create the region first
            $region = (new Region())
                ->setName($this->apiService->getNameBasedOnLanguageFromArray(
                    $language->getCode(),
                    $urlRegion
                ))
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($region);
        }
        if (null !== $urlRegion['locations']) {
            foreach ($urlRegion['locations'] as $locationJson) {
                // Create the Location
                $slugLocation = $this->textService->generateSlugFromClassWithLanguage(
                    $language, Location::class, $locationJson['name']
                );
                $urlLocation = $this->apiService->apiConnect($locationJson['url'])->toArray();
                if (($location = $this->locationRepo->findOneBySlug($slugLocation)) === null) {
                    $location = (new Location())
                        ->setRegion($region)
                        ->setSlug($slugLocation)
                        ->setLanguage($language)
                        ->setName($this->apiService->getNameBasedOnLanguageFromArray(
                            $language->getCode(),
                            $urlLocation
                        ))
                    ;
                    $this->entityManager->persist($location);
                }
                if (null !== $urlLocation['areas']) {
                    // Create the LocationArea
                    foreach ($urlLocation['areas'] as $locationAreaJson) {
                        $slugLocationArea = $this->textService->generateSlugFromClassWithLanguage(
                            $language, LocationArea::class, $locationAreaJson['name']
                        );
                        if (($locationArea = $this->locationRepo->findOneBySlug($slugLocationArea)) === null) {
                            $urlLocationArea = $this->apiService->apiConnect($locationAreaJson['url'])->toArray();
                            $locationArea = (new LocationArea())
                                ->setSlug($slugLocationArea)
                                ->setIdApi($urlLocationArea['id'])
                                ->setNameApi($urlLocationArea['name'])
                                ->setLocation($location)
                                ->setLanguage($language)
                                // Names doesn't exist in other languages than english right now... So let's set in English and adapt later
                                ->setName($this->apiService->getNameBasedOnLanguageFromArray(
                                    'en',
                                    $locationArea
                                ))
                            ;
                        }
                        $this->entityManager->persist($locationArea);
                    }
                }
            }
        }
        $this->entityManager->flush();
    }

}
