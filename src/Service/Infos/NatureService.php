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
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language,
            Nature::class,
            $urlContent['name']
        );
        $codeLang = $language->getCode();

        if (null === $this->natureRepository->findOneBySlug($slug))
        {
            $urlContent = $this->apiManager->apiConnect($urlContent['url'])->toArray();
            $decreasedStat = $this->getModifiedStat($codeLang, $urlContent['decreased_stat']);
            $increasedStat = $this->getModifiedStat($codeLang, $urlContent['increased_stat']);
            $nature = (new Nature())
                ->setSlug($slug)
                ->setName($this->apiManager->getNameBasedOnLanguageFromArray($codeLang, $urlContent))
                ->setLanguage($language)
                ->setStatDecreased($decreasedStat)
                ->setStatIncreased($increasedStat)
            ;
            $this->entityManager->persist($nature);
            $this->entityManager->flush();
        }
    }

    /**
     * @param $codeLang
     * @param $urlContent
     * @return string|null
     * @throws TransportExceptionInterface
     */
    public function getModifiedStat($codeLang, $urlContent): ?string
    {
        if (!empty($urlContent))
        {
            return $this->apiManager->getNameBasedOnLanguage($codeLang, $urlContent['url']);
        }
        return null;
    }

}