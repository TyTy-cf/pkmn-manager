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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LocationService extends AbstractService
{
    /**
     * @var LocationRepository $locationRepo
     */
    private LocationRepository $locationRepo;

    /**
     * LocationService constructor.
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityService
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        LocationRepository $locationRepo,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->locationRepo = $locationRepo;
        parent::__construct($entityService, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @param Region $region
     * @throws TransportExceptionInterface
     */
    public function createLocationFromUrl(Language $language, $apiResponse, Region $region)
    {
        if (null !== $apiResponse['locations']) {
            foreach ($apiResponse['locations'] as $locationJson) {
                // Create the Location
                $urlLocation = $this->apiService->apiConnect($locationJson['url'])->toArray();
                $name = $this->apiService->getNameBasedOnLanguageFromArray(
                    $language->getCode(), $urlLocation
                );
                if ($name !== null) {
                    $slugLocation = $this->textService->generateSlugFromClassWithLanguage(
                        $language, Location::class, $locationJson['name']
                    );
                    if (null === $location = $this->locationRepo->findOneBySlug($slugLocation)) {
                        $location = new Location();
                    }

                    $location->setRegion($region)
                        ->setSlug($region->getSlug() . '-' .$this->textService->slugify($name))
                        ->setLanguage($language)
                        ->setName($name)
                    ;
                    $this->entityManager->persist($location);
                }
            }
        }
    }
}
