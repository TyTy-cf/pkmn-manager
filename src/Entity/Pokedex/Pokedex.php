<?php

namespace App\Entity\Pokedex;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinTable(name="pokedex_version_group",
     *      joinColumns={@JoinColumn(name="pokedex_id", referencedColumnName="id", nullable=true)},
     *      inverseJoinColumns={@JoinColumn(name="version_group_id", referencedColumnName="id", nullable=true)}
 *      )
     */
    private Collection $versionsGroup;

    /**
     * Pokedex constructor.
     */
    public function __construct()
    {
        $this->versionsGroup = new ArrayCollection();
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
        if (!$this->versionsGroup->contains($versionGroup)) {
            $this->versionsGroup->add($versionGroup);
        }
    }

    /**
     * @return mixed
     */
    public function getVersionsGroup(): Collection
    {
        return $this->versionsGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     */
    public function removeVersionGroup(VersionGroup $versionGroup)
    {
        if ($this->versionsGroup->contains($versionGroup)) {
            $this->versionsGroup->removeElement($versionGroup);
        }
    }

}
