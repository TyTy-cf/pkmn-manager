<?php


namespace App\Manager\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveDescription;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\MoveDescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class MoveDescriptionManager extends AbstractManager
{

    /**
     * @var MoveDescriptionRepository $repo
     */
    private MoveDescriptionRepository $repo;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * MoveDescriptionManager constructor.
     * @param MoveDescriptionRepository $repo
     * @param ApiManager $apiManager
     * @param VersionGroupManager $versionGroupManager
     * @param TextManager $textManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        MoveDescriptionRepository $repo,
        ApiManager $apiManager,
        VersionGroupManager $versionGroupManager,
        TextManager $textManager,
        EntityManagerInterface $em
    )
    {
        $this->repo = $repo;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param Language $lang
     * @param string $slug
     * @return MoveDescription|null
     * @throws NonUniqueResultException
     */
    private function getMoveDescriptionByLanguageAndSlug(Language $lang, string $slug): ?MoveDescription
    {
        return $this->repo->getMoveDescriptionByLanguageAndSlug($lang, $slug);
    }

    /**
     * @param Language $lang
     * @param Move $move
     * @param array $apiResponse
     * @throws NonUniqueResultException
     */
    public function createMoveDescription(Language $lang, Move $move, array $apiResponse)
    {
        foreach($apiResponse as $descriptionDetailed)
        {
            if ($descriptionDetailed['language']['name'] === $lang->getCode())
            {
                $slugVersion = $this->textManager->generateSlugFromClass(
                    VersionGroup::class,
                    $descriptionDetailed['version_group']['name']
                );
                $slug = $this->textManager->generateSlugFromClass(
                    MoveDescription::class,
                    $move->getSlug().'-'.$slugVersion
                );
                if ($this->getMoveDescriptionByLanguageAndSlug($lang, $slug) === null)
                {
                    $description = $descriptionDetailed['flavor_text'];
                    $versionGroup = $this->versionGroupManager->getVersionGroupByLanguageAndSlug($lang, $slugVersion);
                    $moveDescription = new MoveDescription();
                    $moveDescription->setMove($move);
                    $moveDescription->setSlug($slug);
                    $moveDescription->setLanguage($lang);
                    $moveDescription->setVersionGroup($versionGroup);
                    $moveDescription->setDescription($this->textManager->removeReturnLineFromText($description));
                    $this->entityManager->persist($moveDescription);
                }
            }
        }
    }

}