<?php


namespace App\Service\Moves;


use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Moves\DamageClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DamageClassService extends AbstractService
{
    /**
     * @var DamageClassRepository
     */
    private DamageClassRepository $damageClassRepository;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param DamageClassRepository $damageClassRepository
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        DamageClassRepository $damageClassRepository,
        ApiService $apiService,
        TextService $textService
    ) {
        $this->damageClassRepository = $damageClassRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiDamageClass
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiDamageClass)
    {
        //Check if the data exist in databases
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, DamageClass::class, $apiDamageClass['name']
        );

        if (null === $this->damageClassRepository->findOneBySlug($slug))
        {
            //Fetch URL details type
            $urlDamageClassDetailed = $this->apiService->apiConnect($apiDamageClass['url'])->toArray();
            // Fetch name & description according the language
            $damageClassNameLang = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDamageClassDetailed
            );
            $damageClass = (new DamageClass())
                ->setName(ucfirst($damageClassNameLang))
                ->setSlug($slug)
                ->setLanguage($language)
                ->setImage('/images/moves/damage_class/' . $slug . '.png')
            ;
            $this->entityManager->persist($damageClass);
            $this->entityManager->flush();
        }
    }
}