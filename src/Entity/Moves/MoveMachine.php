<?php


namespace App\Entity\Moves;


use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use App\Entity\Versions\VersionGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class MachineMove
 * @package App\Entity\Moves
 * @Entity
 * @ORM\Table(name="move_machine")
 */
class MoveMachine
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", length=6)
     */
    private int $id;

    use TraitNames;

    use TraitLanguage;

    use TraitDescription;

    use TraitSlug;

    /**
     * @var Move $move
     *
     * @ManyToOne(targetEntity="App\Entity\Moves\Move")
     * @JoinColumn(name="move_id", referencedColumnName="id")
     */
    private Move $move;

    /**
     * @var string|null
     * @ORM\Column(name="image_url", type="string", length=255)
     */
    private ?string $imageUrl;

    /**
     * @var int|null
     * @ORM\Column(name="cost", type="integer", length=8)
     */
    private ?int $cost;

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
     */
    public function setMove(Move $move): void
    {
        $this->move = $move;
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
     */
    public function setVersionGroup(VersionGroup $versionGroup): void
    {
        $this->versionGroup = $versionGroup;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param string|null $imageUrl
     */
    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return int|null
     */
    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * @param int|null $cost
     */
    public function setCost(?int $cost): void
    {
        $this->cost = $cost;
    }

}