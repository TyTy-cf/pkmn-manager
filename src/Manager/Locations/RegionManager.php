<?php


namespace App\Manager\Locations;


use App\Entity\Locations\Location;
use App\Entity\Locations\LocationArea;
use App\Entity\Locations\Region;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Locations\LocationRepository;
use App\Repository\Locations\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RegionManager extends AbstractManager
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
     * RegionManager constructor.
     * @param RegionRepository $regionRepo
     * @param LocationRepository $locationRepo
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        RegionRepository $regionRepo,
        LocationRepository $locationRepo,
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->regionRepo = $regionRepo;
        $this->locationRepo = $locationRepo;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Region|null
     */
    public function getRegionBySlug(string $slug)
    {
        return $this->regionRepo->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Region::class, $apiResponse['name']
        );
        //Fetch URL details type
        $urlRegion = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        if (($region = $this->getRegionBySlug($slug)) === null)
        {
            // Create the region first
            $region = (new Region())
                ->setName($this->apiManager->getNameBasedOnLanguageFromArray(
                    $language->getCode(),
                    $urlRegion
                ))
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($region);
        }
        if ($urlRegion['locations'] !== null) {
            foreach ($urlRegion['locations'] as $locationJson) {
                // Create the Location
                $slugLocation = $this->textManager->generateSlugFromClassWithLanguage(
                    $language, Location::class, $locationJson['name']
                );
                $urlLocation = $this->apiManager->getDetailed($locationJson['url'])->toArray();
                if (($location = $this->locationRepo->findOneBySlug($slugLocation)) === null) {
                    $location = (new Location())
                        ->setRegion($region)
                        ->setSlug($slugLocation)
                        ->setLanguage($language)
                        ->setName($this->apiManager->getNameBasedOnLanguageFromArray(
                            $language->getCode(),
                            $urlLocation
                        ))
                    ;
                    $this->entityManager->persist($location);
                }
                if ($urlLocation['areas'] !== null) {
                    // Create the LocationArea
                    foreach ($urlLocation['areas'] as $locationAreaJson) {
                        $slugLocationArea = $this->textManager->generateSlugFromClassWithLanguage(
                            $language, LocationArea::class, $locationAreaJson['name']
                        );
                        if (($locationArea = $this->locationRepo->findOneBySlug($slugLocationArea)) === null) {
                            $urlLocationArea = $this->apiManager->getDetailed($locationAreaJson['url'])->toArray();
                            $locationArea = (new LocationArea())
                                ->setSlug($slugLocationArea)
                                ->setIdApi($urlLocationArea['id'])
                                ->setNameApi($urlLocationArea['name'])
                                ->setLocation($location)
                                ->setLanguage($language)
                                // Names doesn't exist in other languages than english right now... So let's set in English and adapt later
                                ->setName($this->apiManager->getNameBasedOnLanguageFromArray(
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
