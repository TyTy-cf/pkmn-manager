<?php


namespace App\Manager\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveDescription;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\MoveDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class MoveDescriptionManager
{

    /**
     * @var MoveDescriptionRepository $repo
     */
    private MoveDescriptionRepository $repo;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * @var TextManager $textManager
     */
    private TextManager $textManager;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * MoveDescriptionManager constructor.
     * @param MoveDescriptionRepository $repo
     * @param ApiManager $apiManager
     * @param VersionGroupManager $versionGroupManager
     * @param TextManager $textManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        MoveDescriptionRepository $repo,
        ApiManager $apiManager,
        VersionGroupManager $versionGroupManager,
        TextManager $textManager,
        EntityManagerInterface $em
    )
    {
        $this->repo = $repo;
        $this->apiManager = $apiManager;
        $this->versionGroupManager = $versionGroupManager;
        $this->textManager = $textManager;
        $this->em = $em;
    }

    /**
     * @param Language $lang
     * @param Move $move
     * @param string $name
     * @param array $apiResponse
     * @throws NonUniqueResultException
     */
    public function createMoveDescription(Language $lang, Move $move, string $name, array $apiResponse)
    {
        foreach($apiResponse as $descriptionDetailed)
        {
            if ($descriptionDetailed['language']['name'] === $lang->getCode())
            {
                $slugVersion = $this->textManager->generateSlugFromClass(
                    VersionGroup::class,
                    $descriptionDetailed['version_group']['name']
                );
                $slug = $name . '-description-' . $slugVersion;
                if ($this->repo->getMoveDescriptionByLanguageAndSlug($lang, $slug))
                {
                    $description = $descriptionDetailed['flavor_text'];
                    $versionGroup = $this->versionGroupManager->getVersionGroupByLanguageAndSlug($lang, $slugVersion);
                    $moveDescription = new MoveDescription();
                    $moveDescription->setMove($move);
                    $moveDescription->setVersionGroup($versionGroup);
                    $moveDescription->setDescription($description);
                    $this->em->persist($moveDescription);
                }
            }
        }
    }

}