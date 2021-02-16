<?php


namespace App\Service\Infos;


use App\Entity\Infos\Ability;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\AbilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilityService extends AbstractService
{
    /**
     * @var AbilityRepository
     */
    private AbilityRepository $abilitiesRepository;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param AbilityRepository $abilitiesRepository
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextService $textManager,
        AbilityRepository $abilitiesRepository
    )
    {
        $this->abilitiesRepository = $abilitiesRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return Ability|null
     */
    public function getAbilitiesBySlug(string $slug): ?Ability
    {
        return $this->abilitiesRepository->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        //Fetch URL details type
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();

        if (!empty($urlDetailed['pokemon']))
        {
            //Check if the data exist in databases
            $slug = $this->textManager->generateSlugFromClassWithLanguage($language, Ability::class, $apiResponse['name']);

            if ($this->getAbilitiesBySlug($slug) === null)
            {
                // Fetch name & description according the language
                $abilityNameLang = $this->apiManager->getNameBasedOnLanguageFromArray(
                    $language->getCode(), $urlDetailed
                );

                if ($abilityNameLang !== null)
                {
                    $abilityDescription = $this->textManager->removeReturnLineFromText(
                        $this->apiManager->getFlavorTextBasedOnLanguageFromArray($language->getCode(), $urlDetailed)
                    );

                    $newAbility = (new Ability())
                        ->setName($abilityNameLang)
                        ->setDescription($abilityDescription)
                        ->setSlug($slug)
                        ->setLanguage($language)
                    ;
                    $this->entityManager->persist($newAbility);
                    $this->entityManager->flush();
                }
            }
        }
    }

}