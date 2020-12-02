<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Nature;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Infos\NatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NatureManager extends AbstractManager
{
    /**
     * @var NatureRepository
     */
    private NatureRepository $natureRepository;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param NatureRepository $natureRepository
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        NatureRepository $natureRepository,
        ApiManager $apiManager,
        TextManager $textManager
    )
    {
        $this->natureRepository = $natureRepository;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * Create a Nature if not already existing in DB
     *
     * @param Language $language
     * @param $urlContent
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $urlContent)
    {
        $slug = $this->textManager->generateSlugFromClass(Nature::class, $urlContent['name']);
        $codeLang = $language->getCode();

        if ($this->natureRepository->getNatureByLanguageAndSlug($codeLang, $slug) === null)
        {
            $urlContent = $this->apiManager->getDetailed($urlContent['url'])->toArray();
            $decreasedStat = $this->getModifiedStat($codeLang, $urlContent['decreased_stat']);
            $increasedStat = $this->getModifiedStat($codeLang, $urlContent['increased_stat']);
            $nature = new Nature();
            $nature->setSlug($slug);
            $nature->setName($this->apiManager->getNameBasedOnLanguageFromArray($codeLang, $urlContent));
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