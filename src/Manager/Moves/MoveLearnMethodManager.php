<?php


namespace App\Manager\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Moves\MoveLearnMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveLearnMethodManager extends AbstractManager
{

    private array $arrayTraduction = [
        'level-up' => 'Montée de niveau',
        'egg' => 'Oeuf',
        'tutor' => 'Maître des capacités',
        'machine' => 'Capsule technique',
        'form-change' => 'Changement de forme',
    ];

    /**
     * @var MoveLearnMethodRepository
     */
    private MoveLearnMethodRepository $repo;

    /**
     * MoveLearnMethodManager constructor.
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param EntityManagerInterface $entityManager
     * @param MoveLearnMethodRepository $repo
     */
    public function __construct
    (
        ApiManager $apiManager,
        TextManager $textManager,
        EntityManagerInterface $entityManager,
        MoveLearnMethodRepository $repo
    )
    {
        $this->repo = $repo;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return MoveLearnMethod|null
     * @throws NonUniqueResultException
     */
    public function getMoveLearnMethodByLanguageAndSlug(Language $language, string $slug): ?MoveLearnMethod
    {
        return $this->repo->getMoveLearnMethodByLanguageAndSlug($language, $slug);
    }

    /**
     * @param Language $language
     * @return array
     */
    public function getAllMoveLearnMethodByLanguage(Language $language)
    {
        return $this->repo->findBy(['language' => $language]);
    }

    /**
     * @return array
     */
    public function getAllMoveLearnMethod()
    {
        return $this->repo->findAll();
    }

    /**
     * @param Language $language
     * @param $apiLearnMethod
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    public function createFromApiResponse(Language $language, $apiLearnMethod)
    {
        //Fetch URL details type
        $urlLearnMethodDetailed = $this->apiManager->getDetailed($apiLearnMethod['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClass(MoveLearnMethod::class, $urlLearnMethodDetailed['name']);

        if ($this->getMoveLearnMethodByLanguageAndSlug($language, $slug) === null)
        {
            // For french there is currently no traduction avalaible, we'll actually make traduction there
            if (array_key_exists($urlLearnMethodDetailed['name'], $this->arrayTraduction))
            {
                $description = $this->apiManager->getFieldContentFromLanguage('en', $urlLearnMethodDetailed, 'descriptions', 'description');
                $learnMethod = new MoveLearnMethod();
                $learnMethod->setSlug($slug);
                $learnMethod->setLanguage($language);
                $learnMethod->setName($this->arrayTraduction[$urlLearnMethodDetailed['name']]);
                $learnMethod->setDescription($description);
                $this->entityManager->persist($learnMethod);
            }
            $this->entityManager->flush();
        }
    }
}