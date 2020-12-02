<?php


namespace App\Manager\Moves;


use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Moves\DamageClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DamageClassManager extends AbstractManager
{
    /**
     * @var DamageClassRepository
     */
    private DamageClassRepository $damageClassRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param DamageClassRepository $damageClassRepository
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        DamageClassRepository $damageClassRepository,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->damageClassRepository = $damageClassRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * The name is always in english and the slug is generate there
     *
     * @param Language $language
     * @param string $slug
     * @return DamageClass|null
     * @throws NonUniqueResultException
     */
    public function getDamageClassByLanguageAndSlug(Language $language, string $slug): ?DamageClass
    {
        return $this->damageClassRepository->getDamageClassByLanguageAndSlug(
            $language,
            $slug
        );
    }

    /**
     * @param Language $language
     * @param $apiDamageClass
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiDamageClass)
    {
        //Check if the data exist in databases
        $slug = $this->textManager->generateSlugFromClass(DamageClass::class, $apiDamageClass['name']);

        if (($newDamageClass = $this->getDamageClassByLanguageAndSlug($language, $slug)) == null)
        {
            //Fetch URL details type
            $urlDamageClassDetailed = $this->apiManager->getDetailed($apiDamageClass['url'])->toArray();
            // Fetch name & description according the language
            $damageClassNameLang = $this->apiManager->getNameBasedOnLanguageFromArray($language->getCode(), $urlDamageClassDetailed);
            $damageClass = new DamageClass();
            $damageClass->setName(ucfirst($damageClassNameLang));
            $damageClass->setSlug($slug);
            $damageClass->setLanguage($language);
            $damageClass->setImage('/images/moves/damage_class/' . $slug . '.png');
            $this->entityManager->persist($damageClass);
            $this->entityManager->flush();
        }
    }
}