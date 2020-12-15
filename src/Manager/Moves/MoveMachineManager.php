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
     * @var MoveManager $movesManager
     */
    private MoveManager $movesManager;

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
     * @param MoveManager $movesManager
     * @param VersionGroupManager $versionGroupManager
     * @param MoveMachineRepository $moveMachineRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TextManager $textManager,
        MoveManager $movesManager,
        VersionGroupManager $versionGroupManager,
        MoveMachineRepository $moveMachineRepository
    )
    {
        $this->moveMachineRepository = $moveMachineRepository;
        $this->movesManager = $movesManager;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return MoveMachine|null
     */
    private function getMoveMachineBySlug(string $slug): ?object
    {
        return $this->moveMachineRepository->findOneBySlug($slug);
    }

    /**
     * @param Move $move
     * @param $array
     * @return MoveMachine[]|array
     */
    public function getMoveMachineByMove(Move $move, $array) {
        return $this->moveMachineRepository->getMachineByMove($move, $array);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, MoveMachine::class, $urlDetailed['item']['name'].'-version-group-'.$urlDetailed['version_group']['name']
        );
        if ($this->getMoveMachineBySlug($slug) === null)
        {
            $move = $this->movesManager->getMoveBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language,Move::class, $urlDetailed['move']['name']
                )
            );
            if ($move !== null)
            {
                $groupVersion = $this->versionGroupManager->getVersionGroupBySlug(
                    $this->textManager->generateSlugFromClassWithLanguage(
                        $language, VersionGroup::class, $urlDetailed['version_group']['name']
                    )
                );
                $urlDetailedItem = $this->apiManager->getDetailed($urlDetailed['item']['url'])->toArray();

                $moveMachine = (new MoveMachine())
                    ->setLanguage($language)
                    ->setSlug($slug)
                    ->setVersionGroup($groupVersion)
                    ->setName($this->apiManager->getNameBasedOnLanguageFromArray(
                        $language->getCode(),
                        $urlDetailedItem
                    ))
                    ->setMove($move)
                    ->setCost($urlDetailedItem['cost'])
                ;

                if (isset($urlDetailedItem['sprites']['default']))
                {
                    $moveMachine->setImageUrl($urlDetailedItem['sprites']['default']);
                }
                // Fetch the description
                foreach($urlDetailedItem['flavor_text_entries'] as $flavorTextEntry)
                {
                    $slugVersion = $this->textManager->generateSlugFromClassWithLanguage(
                        $language,
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