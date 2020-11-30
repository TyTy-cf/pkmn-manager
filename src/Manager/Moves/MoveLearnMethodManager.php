<?php


namespace App\Manager\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveLearnMethodManager
{
    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TextManager $textManager
     */
    private TextManager $textManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    private array $arrayTraduction = [
        'level-up' => 'Montée de niveau',
        'egg' => 'Oeuf',
        'tutor' => 'Maître des capacités',
        'machine' => 'Capsule technique',
        'form-change' => 'Changement de forme',
    ];

    /**
     * MoveLearnMethodManager constructor.
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param LanguageManager $languageManager
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ApiManager $apiManager,
        TextManager $textManager,
        LanguageManager $languageManager,
        EntityManagerInterface $entityManager)
    {
        $this->apiManager = $apiManager;
        $this->textManager = $textManager;
        $this->languageManager = $languageManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $lang
     * @param $apiLearnMethod
     * @throws TransportExceptionInterface
     */
    public function createMoveLearnMethodIfNotExist(string $lang, $apiLearnMethod)
    {
        //Fetch URL details type
        $urlLearnMethod = $apiLearnMethod['url'];
        $urlLearnMethodDetailed = $this->apiManager->getDetailed($urlLearnMethod)->toArray();

        // For french there is currently no traduction avalaible, we'll actually make traduction there
        if (array_key_exists($urlLearnMethodDetailed['name'], $this->arrayTraduction))
        {
            $language = $this->languageManager->getLanguageByCode($lang);
            $slug = $this->textManager->generateSlugFromClass(MoveLearnMethod::class, $urlLearnMethodDetailed['name']);
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