<?php


namespace App\Service\Infos;


use App\Entity\Infos\Ability;
use App\Entity\Infos\AbilityVersionGroup;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Repository\Infos\AbilityVersionGroupRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\AbilityRepository;
use App\Service\Versions\VersionGroupService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilityService extends AbstractService
{
    /**
     * @var AbilityRepository $abilitiesRepository
     */
    private AbilityRepository $abilitiesRepository;

    /**
     * @var AbilityVersionGroupRepository $abilityVersionGroupRepository
     */
    private AbilityVersionGroupRepository $abilityVersionGroupRepository;

    /**
     * @var VersionGroupService $versionGroupService
     */
    private VersionGroupService $versionGroupService;

    /**
     * AbilityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiService
     * @param TextService $textService
     * @param AbilityRepository $abilitiesRepository
     * @param AbilityVersionGroupRepository $abilityVersionGroupRepository
     * @param VersionGroupService $versionGroupService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ApiService $apiService,
        TextService $textService,
        AbilityRepository $abilitiesRepository,
        AbilityVersionGroupRepository $abilityVersionGroupRepository,
        VersionGroupService $versionGroupService
    ) {
        $this->abilitiesRepository = $abilitiesRepository;
        $this->versionGroupService = $versionGroupService;
        $this->abilityVersionGroupRepository = $abilityVersionGroupRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse) {
        //Fetch URL details type
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();

        if (!empty($urlDetailed['pokemon']))
        {
            //Check if the data exist in databases
            $slug = $this->textService->generateSlugFromClassWithLanguage(
                $language, Ability::class, $apiResponse['name']
            );

            $isNew = false;
            if (null === $ability = $this->abilitiesRepository->findOneBySlug($slug)) {
                $ability = new Ability();
                $isNew = true;
            }

            // Fetch name & description according the language
            $abilityNameLang = $this->apiService->getNameBasedOnLanguageFromArray(
                $language->getCode(), $urlDetailed
            );

            if (null !== $abilityNameLang)
            {
                $abilityEffectEntries = $this->textService->removeReturnLineFromText(
                    $this->apiService->getFieldContentFromLanguage(
                        $language->getCode(),
                        $urlDetailed,
                        'effect_entries',
                        'effect'
                    )
                );

                $ability
                    ->setName($abilityNameLang)
                    ->setDescription($abilityEffectEntries)
                ;
                if ($isNew) {
                    $ability
                        ->setSlug($slug)
                        ->setLanguage($language)
                    ;
                    $this->entityManager->persist($ability);
                }

                // Create the Description by VersionGroup
                $this->createAbilityDescription(
                    $language,
                    $ability,
                    $urlDetailed['flavor_text_entries']
                );

                $this->entityManager->flush();
            }
        }
    }

    /**
     * @param Language $language
     * @param Ability $ability
     * @param $urlDetailed
     */
    private function createAbilityDescription(
        Language $language,
        Ability $ability,
        $urlDetailed
    ) {
        foreach($urlDetailed as $descriptionDetailed) {
            if ($descriptionDetailed['language']['name'] === $language->getCode()) {
                $slugVersion = $this->textService->generateSlugFromClassWithLanguage(
                    $language,
                    VersionGroup::class,
                    $descriptionDetailed['version_group']['name']
                );
                $slug = $this->textService->generateSlugFromClassWithLanguage(
                    $language,
                    AbilityVersionGroup::class,
                    $ability->getSlug().'-'.$slugVersion
                );

                $isNew = false;
                if (null === $abilityVersionGroup = $this->abilityVersionGroupRepository->findOneBySlug($slug)) {
                    $abilityVersionGroup = new AbilityVersionGroup();
                    $isNew = true;
                }
                $versionGroup = $this->versionGroupService->getVersionGroupBySlug($slugVersion);
                $abilityVersionGroup
                    ->setVersionGroup($versionGroup)
                    ->setAbility($ability)
                    ->setDescription($this->textService->removeReturnLineFromText(
                        $descriptionDetailed['flavor_text']
                    ))
                ;
                if ($isNew) {
                    $abilityVersionGroup
                        ->setSlug($slug)
                        ->setLanguage($language)
                    ;
                    $this->entityManager->persist($abilityVersionGroup);
                }
                $this->entityManager->persist($abilityVersionGroup);
            }
        }
    }

}
