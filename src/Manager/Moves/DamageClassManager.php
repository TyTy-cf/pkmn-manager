<?php


namespace App\Manager\Moves;


use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Moves\DamageClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DamageClassManager
{

    /**
     * @var DamageClassRepository $damageClassRepository
     */
    private DamageClassRepository $damageClassRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TextManager
     */
    private TextManager $textManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param DamageClassRepository $damageClassRepository
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                DamageClassRepository $damageClassRepository,
                                LanguageManager $languageManager,
                                ApiManager $apiManager,
                                TextManager $textManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->damageClassRepository = $damageClassRepository;
    }

    /**
     * @param string $lang
     * @param $apiDamageClass
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createDamageClassIfNotExist(string $lang, $apiDamageClass)
    {
        //Fetch URL details type
        $urlDamageClass = $apiDamageClass['url'];
        $urlDamageClassDetailed = $this->apiManager->getDetailed($urlDamageClass)->toArray();
        // Fetch the right language
        $language = $this->languageManager->getLanguageByCode($lang);

        //Check if the data exist in databases
        $slug = 'damage-class-'. $apiDamageClass['name'];

        if (($newDamageClass = $this->damageClassRepository->getDamageClassByLanguageAndSlug($lang, $slug)) == null)
        {
            // Fetch name & description according the language
            $damageClassNameLang = $this->apiManager->getNameBasedOnLanguageFromArray($lang, $urlDamageClassDetailed['names']);
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