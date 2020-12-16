<?php


namespace App\Entity\Versions;


use App\Entity\Locations\Region;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Versions\GenerationRepository")
 * @ORM\Table(name="generation")
 */
class Generation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $number;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private string $code;

    /**
     * @var Region
     *
     * @OneToOne(targetEntity="App\Entity\Locations\Region", inversedBy="generation")
     * @JoinColumn(name="main_region_id", referencedColumnName="id")
     */
    private Region $mainRegion;

    /**
     * @var int $displayOrder
     * @ORM\Column(name="display_order", type="integer", nullable=true)
     */
    private int $displayOrder;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Versions\VersionGroup", mappedBy="generation")
     * @var Collection
     */
    private Collection $versionsGroup;

    /**
     * @var array|string[]
     */
    public static array $relationArray = [
        1 => 'RB',
        2 => 'OA',
        3 => 'RS',
        4 => 'DP',
        5 => 'BW',
        6 => 'XY',
        7 => 'SM',
        8 => 'SS'
    ];

    /**
     * Generation constructor.
     */
    public function __construct()
    {
        $this->versionsGroup = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Generation
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Generation
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return Region
     */
    public function getMainRegion(): Region
    {
        return $this->mainRegion;
    }

    /**
     * @param Region $mainRegion
     * @return Generation
     */
    public function setMainRegion(Region $mainRegion): self
    {
        $this->mainRegion = $mainRegion;
        return $this;
    }

    /**
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    /**
     * @param int $displayOrder
     * @return Generation
     */
    public function setDisplayOrder(int $displayOrder): Generation
    {
        $this->displayOrder = $displayOrder;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getVersionsGroup() {
        return $this->versionsGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     */
    public function addVersionsGroup(VersionGroup $versionGroup) {
        if (!$this->versionsGroup->contains($versionGroup)) {
            $this->versionsGroup[] = $versionGroup;
        }
    }

    /**
     * @param VersionGroup $versionGroup
     */
    public function removeVersionsGroup(VersionGroup $versionGroup) {
        if ($this->versionsGroup->contains($versionGroup)) {
            $this->versionsGroup->removeElement($versionGroup);
        }
    }

}