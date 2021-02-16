<?php


namespace App\Service\Moves;


use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Moves\DamageClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
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
    )
    {
        $this->damageClassRepository = $damageClassRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return DamageClass|null
     */
    public function getDamageClassBySlug(string $slug): ?object
    {
        return $this->damageClassRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiDamageClass
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiDamageClass)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, DamageClass::class, $apiDamageClass['name']
        );

        if ($this->getDamageClassBySlug($slug) === null)
        {
            //Fetch URL details type
            $urlDamageClassDetailed = $this->apiManager->apiConnect($apiDamageClass['url'])->toArray();
            // Fetch name & description according the language
            $damageClassNameLang = $this->apiManager->getNameBasedOnLanguageFromArray(
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