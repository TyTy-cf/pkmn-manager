<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveDescription;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextManager;
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
     * @param ApiService $apiManager
     * @param VersionGroupService $versionGroupManager
     * @param TextManager $textManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        MoveDescriptionRepository $repo,
        ApiService $apiManager,
        VersionGroupService $versionGroupManager,
        TextManager $textManager,
        EntityManagerInterface $em
    )
    {
        $this->repo = $repo;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return MoveDescription|null
     */
    public function getMoveDescriptionBySlug(string $slug): ?MoveDescription
    {
        return $this->repo->findOneBySlug($slug);
    }

    /**
     * @param Move $move
     * @param $array
     * @return MoveDescription[]|array
     */
    public function getMoveDescriptionByMove(Move $move)
    {
        return $this->repo->getMoveDescriptionByMove($move);
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
                $slugVersion = $this->textManager->generateSlugFromClassWithLanguage(
                    $lang,
                    VersionGroup::class,
                    $descriptionDetailed['version_group']['name']
                );
                $slug = $this->textManager->generateSlugFromClassWithLanguage(
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
                        ->setDescription($this->textManager->removeReturnLineFromText(
                            $description
                        ))
                    ;
                    $this->entityManager->persist($moveDescription);
                }
            }
        }
    }

}