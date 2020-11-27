<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Nature;
use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\NatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NatureManager
{
    /**
     * @var NatureRepository
     */
    private NatureRepository $natureRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param NatureRepository $natureRepository
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager,
                                NatureRepository $natureRepository,
                                ApiManager $apiManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->natureRepository = $natureRepository;
    }

    /**
     * Create a Nature if not already existing in DB
     *
     * @param Language $language
     * @param array $urlContent
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createNatureIfNotExist(Language $language, array $urlContent)
    {
        $slug = 'nature-'. $urlContent['name'];
        $codeLang = $language->getCode();
        if (($nature = $this->natureRepository->getNatureByLanguageAndSlug($codeLang, $slug)) == null)
        {
            $decreasedStat = $this->getModifiedStat($codeLang, $urlContent['decreased_stat']);
            $increasedStat = $this->getModifiedStat($codeLang, $urlContent['increased_stat']);
            $nature = new Nature();
            $nature->setSlug($slug);
            $nature->setName($this->apiManager->getNameBasedOnLanguageFromArray($codeLang, $urlContent['names']));
            $nature->setLanguage($language);
            $nature->setStatDecreased($decreasedStat);
            $nature->setStatsIncreased($increasedStat);
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