<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveDescription;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Moves\MoveDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class MoveDescriptionService extends AbstractService
{

    /**
     * @var MoveDescriptionRepository $repo
     */
    private MoveDescriptionRepository $repo;

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * MoveDescriptionService constructor.
     * @param MoveDescriptionRepository $repo
     * @param ApiService $apiService
     * @param VersionGroupService $versionGroupService
     * @param TextService $textService
     * @param EntityManagerInterface $em
     */
    public function __construct(
        MoveDescriptionRepository $repo,
        ApiService $apiService,
        VersionGroupService $versionGroupService,
        TextService $textService,
        EntityManagerInterface $em
    ) {
        $this->repo = $repo;
        $this->versionGroupManager = $versionGroupService;
        parent::__construct($em, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return MoveDescription|null
     */
    public function getMoveDescriptionBySlug(string $slug): ?MoveDescription {
        return $this->repo->findOneBySlug($slug);
    }

    /**
     * @param Language $lang
     * @param Move $move
     * @param array $apiResponse
     */
    public function createMoveDescription(Language $lang, Move $move, array $apiResponse)
    {
        foreach($apiResponse as $descriptionDetailed)
        {
            if ($descriptionDetailed['language']['name'] === $lang->getCode())
            {
                $slugVersion = $this->textService->generateSlugFromClassWithLanguage(
                    $lang,
                    VersionGroup::class,
                    $descriptionDetailed['version_group']['name']
                );
                $slug = $this->textService->generateSlugFromClassWithLanguage(
                    $lang,
                    MoveDescription::class,
                    $move->getSlug().'-'.$slugVersion
                );
                if ($this->getMoveDescriptionBySlug($slug) === null)
                {
                    $description = $descriptionDetailed['flavor_text'];
                    $versionGroup = $this->versionGroupManager->getVersionGroupBySlug($slugVersion);
                    $moveDescription = (new MoveDescription())
                        ->setMove($move)
                        ->setSlug($slug)
                        ->setLanguage($lang)
                        ->setVersionGroup($versionGroup)
                        ->setDescription($this->textService->removeReturnLineFromText(
                            $description
                        ))
                    ;
                    $this->entityManager->persist($moveDescription);
                }
            }
        }
    }

}
