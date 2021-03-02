<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitSlug;
use App\Entity\Versions\VersionGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class MoveDescription
 * @package App\Entity\Infos
 *
 * @ORM\Table(name="move_description")
 * @ORM\Entity(repositoryClass="App\Repository\Moves\MoveDescriptionRepository")
 */
class MoveDescription
{
    /**
     * @var int $id id de l'abstract info
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitLanguage;

    use TraitSlug;

    use TraitDescription;

    /**
     * @var Move $move
     *
     * @ManyToOne(targetEntity="App\Entity\Moves\Move", inversedBy="movesDescription")
     * @JoinColumn(name="move_id", referencedColumnName="id")
     */
    private Move $move;

    /**
     * @var VersionGroup $versionGroup
     *
     * @ManyToOne(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinColumn(name="version_group_id", referencedColumnName="id")
     */
    private VersionGroup $versionGroup;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Move
     */
    public function getMove(): Move
    {
        return $this->move;
    }

    /**
     * @param Move $move
     * @return MoveDescription
     */
    public function setMove(Move $move): self
    {
        $this->move = $move;
        return $this;
    }

    /**
     * @return VersionGroup
     */
    public function getVersionGroup(): VersionGroup
    {
        return $this->versionGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     * @return MoveDescription
     */
    public function setVersionGroup(VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

}
