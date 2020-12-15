<?php


namespace App\Manager\Pokedex;


use App\Entity\Pokedex\EvolutionTrigger;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Pokedex\EvolutionTriggerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionTriggerManager extends AbstractManager
{
    /**
     * @var EvolutionTriggerRepository $evolutionTriggerRepo
     */
    private EvolutionTriggerRepository $evolutionTriggerRepo;

    /**
     * EvolutionTriggerManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param EvolutionTriggerRepository $evolutionTriggerRepo
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        EvolutionTriggerRepository $evolutionTriggerRepo
    )
    {
        $this->evolutionTriggerRepo = $evolutionTriggerRepo;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return EvolutionTrigger|null
     */
    public function getEvolutionTriggerBySlug(string $slug)
    {
        return $this->evolutionTriggerRepo->findOneBySlug($slug);
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
            $language, EvolutionTrigger::class, $apiResponse['name']
        );
        if ($this->getEvolutionTriggerBySlug($slug) === null)
        {
            //Fetch URL details type
            $urlEvolutionTrigger = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
            $eggGroup = (new EvolutionTrigger())
                ->setName($this->apiManager->getNameBasedOnLanguageFromArray(
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
