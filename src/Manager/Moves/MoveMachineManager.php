<?php


namespace App\Manager\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveMachine;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveMachineManager extends AbstractManager
{

    /**
     * @var MoveRepository
     */
    private MoveRepository $movesRepository;

    /**
     * @var MoveMachineRepository
     */
    private MoveMachineRepository $moveMachineRepository;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * MoveManager constructor
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param MoveRepository $moveRepository
     * @param VersionGroupManager $versionGroupManager
     * @param MoveMachineRepository $moveMachineRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TextManager $textManager,
        MoveRepository $moveRepository,
        VersionGroupManager $versionGroupManager,
        MoveMachineRepository $moveMachineRepository
    )
    {
        $this->moveMachineRepository = $moveMachineRepository;
        $this->movesRepository = $moveRepository;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return MoveMachine|null
     * @throws NonUniqueResultException
     */
    private function getMoveMachineByLanguageAndSlug(Language $language, string $slug): ?MoveMachine
    {
        return $this->moveMachineRepository->getMoveMachineByLanguageAndSlug($language, $slug);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClass(MoveMachine::class, $urlDetailed['item']['name']);
        if ($this->getMoveMachineByLanguageAndSlug($language, $slug) === null)
        {
            $move = $this->movesRepository->getMoveByLanguageAndSlug(
                $language,
                $this->textManager->generateSlugFromClass(Move::class, $urlDetailed['move']['name'])
            );
            if ($move !== null)
            {
                $groupVersion = $this->versionGroupManager->getVersionGroupByLanguageAndSlug(
                    $language,
                    $this->textManager->generateSlugFromClass(VersionGroup::class, $urlDetailed['version_group']['name'])
                );
                $urlDetailedItem = $this->apiManager->getDetailed($urlDetailed['item']['url'])->toArray();
                $moveMachine = new MoveMachine();
                $moveMachine->setLanguage($language);
                $moveMachine->setSlug($slug.'-'.$groupVersion->getSlug());
                $moveMachine->setVersionGroup($groupVersion);
                $moveMachine->setName($this->apiManager->getNameBasedOnLanguageFromArray(
                    $language->getCode(),
                    $urlDetailedItem
                ));
                $moveMachine->setMove($move);
                $moveMachine->setCost($urlDetailedItem['cost']);
                if (isset($urlDetailedItem['sprites']['default']))
                {
                    $moveMachine->setImageUrl($urlDetailedItem['sprites']['default']);
                }
                // Fetch the description
                foreach($urlDetailedItem['flavor_text_entries'] as $flavorTextEntry)
                {
                    $slugVersion = $this->textManager->generateSlugFromClass(
                        VersionGroup::class,
                        $flavorTextEntry['version_group']['name']
                    );
                    if ($flavorTextEntry['language']['name'] === $language->getCode()
                     && $slugVersion === $groupVersion->getSlug())
                    {
                        $moveMachine->setDescription(
                            $this->textManager->removeReturnLineFromText($flavorTextEntry['text'])
                        );
                        break;
                    }
                }
                $this->entityManager->persist($moveMachine);
                $this->entityManager->flush();
            }
        }
    }

}