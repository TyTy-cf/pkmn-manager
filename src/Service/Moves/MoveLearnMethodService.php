<?php


namespace App\Service\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Moves\MoveLearnMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveLearnMethodService extends AbstractService
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
     * MoveLearnMethodService constructor.
     * @param ApiService $apiManager
     * @param TextService $textService
     * @param EntityManagerInterface $entityService
     * @param MoveLearnMethodRepository $repo
     */
    public function __construct
    (
        ApiService $apiManager,
        TextService $textService,
        EntityManagerInterface $entityService,
        MoveLearnMethodRepository $repo
    )
    {
        $this->repo = $repo;
        parent::__construct($entityService, $apiManager, $textService);
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
        $urlLearnMethodDetailed = $this->apiService->apiConnect($apiLearnMethod['url'])->toArray();
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, MoveLearnMethod::class, $urlLearnMethodDetailed['name']
        );

        if ($this->getMoveLearnMethodBySlug($slug) === null)
        {
            // For french there is currently no traduction avalaible, we'll actually make traduction there
            if (array_key_exists($urlLearnMethodDetailed['name'], $this->arrayTraduction))
            {
                $description = $this->apiService->getFieldContentFromLanguage(
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