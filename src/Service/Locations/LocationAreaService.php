<?php


namespace App\Service\Locations;


use App\Entity\Locations\Location;
use App\Entity\Locations\LocationArea;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Locations\LocationAreaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LocationAreaService extends AbstractService
{
    /**
     * @var LocationAreaRepository $areaRepository
     */
    private LocationAreaRepository $locationAreaRepository;

    /**
     * LocationAreaService constructor.
     * @param LocationAreaRepository $locationAreaRepository
     * @param EntityManagerInterface $entityService
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct(
        LocationAreaRepository $locationAreaRepository,
        EntityManagerInterface $entityService,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->locationAreaRepository = $locationAreaRepository;
        parent::__construct($entityService, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @param Location $location
     * @throws TransportExceptionInterface
     */
    public function createAreaFromJsonAndLocation(Language $language, $apiResponse, Location $location)
    {
        // Create the location Area
        if (null !== $apiResponse['areas']) {
            // Create the LocationArea
            foreach ($apiResponse['areas'] as $locationAreaJson) {
                $slugLocationArea = $this->textService->generateSlugFromClassWithLanguage(
                    $language, LocationArea::class, $locationAreaJson['name']
                );
                if (null === $locationArea = $this->locationAreaRepository->findOneBySlug($slugLocationArea)) {
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
