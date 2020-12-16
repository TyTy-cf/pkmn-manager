<?php


namespace App\Manager\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Repository\Moves\MoveLearnMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param string $slug
     * @return MoveLearnMethod|null
     */
    public function getMoveLearnMethodBySlug(string $slug): object
    {
        return $this->repo->findOneBySlug($slug);
    }

    /**
     * @param Language $language
     * @return array
     */
    public function getAllMoveLearnMethodByLanguage(Language $language)
    {
        return $this->repo->getAllMoveLearnMethodByLanguage($language);
    }

    /**
     * @param Language $language
     * @param $apiLearnMethod
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiLearnMethod)
    {
        //Fetch URL details type
        $urlLearnMethodDetailed = $this->apiManager->getDetailed($apiLearnMethod['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, MoveLearnMethod::class, $urlLearnMethodDetailed['name']
        );

        if ($this->getMoveLearnMethodBySlug($slug) === null)
        {
            // For french there is currently no traduction avalaible, we'll actually make traduction there
            if (array_key_exists($urlLearnMethodDetailed['name'], $this->arrayTraduction))
            {
                $description = $this->apiManager->getFieldContentFromLanguage(
                    'en', $urlLearnMethodDetailed, 'descriptions', 'description'
                );

                $learnMethod = (new MoveLearnMethod())
                    ->setSlug($slug)
                    ->setLanguage($language)
                    ->setName($this->arrayTraduction[$urlLearnMethodDetailed['name']])
                    ->setDescription($description)
                ;
                $this->entityManager->persist($learnMethod);
            }
            $this->entityManager->flush();
        }
    }
}