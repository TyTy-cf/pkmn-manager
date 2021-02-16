<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveMachine;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveMachineService extends AbstractService
{

    /**
     * @var MoveService $movesManager
     */
    private MoveService $movesManager;

    /**
     * @var MoveMachineRepository
     */
    private MoveMachineRepository $moveMachineRepository;

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiManager
     * @param TextService $textService
     * @param MoveService $movesService
     * @param VersionGroupService $versionGroupManager
     * @param MoveMachineRepository $moveMachineRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiManager,
        TextService $textService,
        MoveService $movesService,
        VersionGroupService $versionGroupManager,
        MoveMachineRepository $moveMachineRepository
    )
    {
        $this->moveMachineRepository = $moveMachineRepository;
        $this->movesManager = $movesService;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textService);
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
    public function getMoveMachineByMove(Move $move) {
        return $this->moveMachineRepository->getMachineByMove($move);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiManager->apiConnect($apiResponse['url'])->toArray();
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
                $urlDetailedItem = $this->apiManager->apiConnect($urlDetailed['item']['url'])->toArray();

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