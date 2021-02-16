<?php


namespace App\Service\Pokedex;


use App\Entity\Pokedex\EggGroup;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Pokedex\EggGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EggGroupService extends AbstractService
{
    /**
     * @var EggGroupRepository
     */
    private EggGroupRepository $eggGroupRepository;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param EggGroupRepository $eggGroupRepository
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        EggGroupRepository $eggGroupRepository,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->eggGroupRepository = $eggGroupRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiEggGroup
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiEggGroup)
    {
        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, EggGroup::class, $apiEggGroup['name']
        );
        if (null === $this->eggGroupRepository->findOneBySlug($slug))
        {
            //Fetch URL details type
            $urlDamageClassDetailed = $this->apiService->apiConnect($apiEggGroup['url'])->toArray();
            // Fetch name & description according the language
            $eggGroupName = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(),
                $urlDamageClassDetailed
            );
            $eggGroup = (new EggGroup())
                ->setName(ucfirst($eggGroupName))
                ->setSlug($slug)
                ->setLanguage($language)
            ;
            $this->entityManager->persist($eggGroup);
            $this->entityManager->flush();
        }
    }

}