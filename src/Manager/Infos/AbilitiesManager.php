<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Ability;
use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Infos\AbilitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilitiesManager
{
    /**
     * @var AbilitiesRepository $abilitiesRepository
     */
    private AbilitiesRepository $abilitiesRepository;

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
     * @var LanguageManager
     */
    private LanguageManager $languageManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TextManager $textManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ApiManager $apiManager,
                                LanguageManager $languageManager,
                                TextManager $textManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->abilitiesRepository = $this->entityManager->getRepository(Ability::class);
    }

    /**
     * @param string $lang
     * @param $ability
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createAbilityIfNotExist(string $lang, $ability)
    {
        //Fetch URL details type
        $urlAbility = $ability['url'];
        $urlDetailed = $this->apiManager->getDetailed($urlAbility)->toArray();

        if (!empty($urlDetailed['pokemon']))
        {
            // Fetch the right language
            $language = $this->languageManager->getLanguageByCode($lang);

            //Check if the data exist in databases
            $slug = 'ability-'. $ability['name'];

            if (($newAbility = $this->abilitiesRepository->getAbilitiesByLanguageAndSlug($language, $slug)) == null)
            {
                // Fetch name & description according the language
                $abilityNameLang = $this->apiManager->getNameBasedOnLanguageFromArray($lang, $urlDetailed['names']);

                $abilityDescription = $this->textManager->removeCharacter("\n", " ",
                                      $this->apiManager->getFlavorTextBasedOnLanguageFromArray($lang, $urlDetailed));

                $newAbility = new Ability();
                $newAbility->setName($abilityNameLang);
                $newAbility->setDescription($abilityDescription);
                $newAbility->setSlug($slug);
                $newAbility->setLanguage($language);
                $this->entityManager->persist($newAbility);
                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Ability
     * @throws NonUniqueResultException
     */
    public function getAbilitiesByLanguageAndSlug(Language $language, string $slug): Ability
    {
        return $this->abilitiesRepository->getAbilitiesByLanguageAndSlug($language, $slug);
    }
}