<?php


namespace App\Service\Pokedex;


use App\Entity\Pokedex\EvolutionTrigger;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Pokedex\EvolutionTriggerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionTriggerService extends AbstractService
{
    /**
     * @var EvolutionTriggerRepository $evolutionTriggerRepo
     */
    private EvolutionTriggerRepository $evolutionTriggerRepo;

    /**
     * EvolutionTriggerService constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param EvolutionTriggerRepository $evolutionTriggerRepo
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        EvolutionTriggerRepository $evolutionTriggerRepo
    ) {
        $this->evolutionTriggerRepo = $evolutionTriggerRepo;
        parent::__construct($entityManager, $apiService, $textService);
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
            $language, EvolutionTrigger::class, $apiResponse['name']
        );
        if (null === $this->evolutionTriggerRepo->findOneBySlug($slug))
        {
            //Fetch URL details type
            $urlEvolutionTrigger = $this->apiService->apiConnect($apiResponse['url'])->toArray();
            $eggGroup = (new EvolutionTrigger())
                ->setName($this->apiService->getNameBasedOnLanguageFromArray(
                    $language->getCode(),
                    $urlEvolutionTrigger
                ))
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($eggGroup);
            $this->entityManager->flush();
        }
    }
}
