<?php

namespace App\Entity\Pokedex;

use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitNomenclature;
use App\Entity\Versions\VersionGroup;
use App\Repository\Pokedex\PokedexRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=PokedexRepository::class)
 */
class Pokedex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    use TraitNomenclature;

    use TraitDescription;

    /**
     * @var VersionGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinColumn(name="version_group_id")
     */
    private VersionGroup $versionGroup;

    public function getId(): ?int
    {
        return $this->id;
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

}
