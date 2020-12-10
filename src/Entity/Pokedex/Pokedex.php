<?php

namespace App\Entity\Pokedex;

use App\Entity\Locations\Region;
use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Versions\VersionGroup;
use App\Repository\Pokedex\PokedexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass=PokedexRepository::class)
 * @ORM\Table(indexes={
 *     @ORM\Index(
 *          name="slug_idx",
 *          columns={"slug"}
 *     )
 * })
 */
class Pokedex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    use TraitDescription;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Versions\VersionGroup", inversedBy="pokedex")
     * @JoinTable(name="pokedex_version_group")
     */
    private Collection $versionGroup;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="App\Entity\Locations\Region")
     * @JoinColumn(name="region_id", nullable=true)
     */
    private Region $region;

    /**
     * Pokedex constructor.
     */
    public function __construct()
    {
        $this->versionGroup = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param VersionGroup $versionGroup
     */
    public function addVersionGroup(VersionGroup $versionGroup): void
    {
        if (!$this->versionGroup->contains($versionGroup)) {
            $this->versionGroup->add($versionGroup);
        }
    }

    /**
     * @return mixed
     */
    public function getVersionsGroup(): Collection
    {
        return $this->versionGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     */
    public function removeVersionGroup(VersionGroup $versionGroup)
    {
        if ($this->versionGroup->contains($versionGroup)) {
            $this->versionGroup->removeElement($versionGroup);
        }
    }

    /**
     * @return Region
     */
    public function getRegion(): Region
    {
        return $this->region;
    }

    /**
     * @param Region $region
     * @return Pokedex
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }

}
