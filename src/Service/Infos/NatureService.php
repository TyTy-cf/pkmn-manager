<?php


namespace App\Service\Infos;


use App\Entity\Infos\Nature;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Infos\NatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NatureService extends AbstractService
{
    /**
     * @var NatureRepository
     */
    private NatureRepository $natureRepository;

    /**
     * PokemonService constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param NatureRepository $natureRepository
     * @param ApiService $apiService
     * @param TextService $textService
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        NatureRepository $natureRepository,
        ApiService $apiService,
        TextService $textService
    )
    {
        $this->natureRepository = $natureRepository;
        parent::__construct($entityManager, $apiService, $textService);
    }

    /**
     * Create a Nature if not already existing in DB
     *
     * @param Language $language
     * @param $urlContent
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $urlContent)
    {
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language,
            Nature::class,
            $urlContent['name']
        );
        $codeLang = $language->getCode();

        if (null === $nature = $this->natureRepository->findOneBySlug($slug)) {
            $nature = (new Nature())
                ->setSlug($slug)
                ->setName($this->apiService->getNameBasedOnLanguageFromArray($codeLang, $urlContent))
                ->setLanguage($language)
            ;
        }

        $urlContent = $this->apiService->apiConnect($urlContent['url'])->toArray();

        $tmpStats[Nature::$ATK_API] = 1;
        $tmpStats[Nature::$DEF_API] = 1;
        $tmpStats[Nature::$SPA_API] = 1;
        $tmpStats[Nature::$SPD_API] = 1;
        $tmpStats[Nature::$SPE_API] = 1;
        if (isset($urlContent['decreased_stat'])) {
            $tmpStats[$urlContent['decreased_stat']['name']] = 0.9;
        }
        if (isset($urlContent['increased_stat'])) {
            $tmpStats[$urlContent['increased_stat']['name']] = 1.1;
        }
        $nature->setAtk($tmpStats[Nature::$ATK_API]);
        $nature->setDef($tmpStats[Nature::$DEF_API]);
        $nature->setSpa($tmpStats[Nature::$SPA_API]);
        $nature->setSpd($tmpStats[Nature::$SPD_API]);
        $nature->setSpe($tmpStats[Nature::$SPE_API]);

        $this->entityManager->persist($nature);
        $this->entityManager->flush();
    }

}
