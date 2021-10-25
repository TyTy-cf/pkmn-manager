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

/**
 * Class EggGroupService
 * @package App\Service\Pokedex
 *
 * @property EggGroupRepository $eggGroupRepository
 */
class EggGroupService extends AbstractService
{

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
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $apiEggGroup)
    {
        $languageCode = $language->getCode();
        $urlDamageClassDetailed = $this->apiService->apiConnect($apiEggGroup['url'])->toArray();
        $eggGroupName = $this->apiService->getNameBasedOnLanguageFromArray($languageCode, $urlDamageClassDetailed);
        $slug = $languageCode . '-' . $this->textService->slugify($eggGroupName);
        $isNew = false;

        if (null === $eggGroup = $this->eggGroupRepository->findOneBySlugAndLanguage($slug, $languageCode))
        {
            $eggGroup = (new EggGroup())->setLanguage($language);
            $isNew = true;
        }
        $slug = $this->textService->slugify($eggGroupName);
        $eggGroup
            ->setName(ucfirst($eggGroupName))
            ->setSlug($slug)
        ;
        if ($isNew) {
            $this->entityManager->persist($eggGroup);
        }
    }

}
