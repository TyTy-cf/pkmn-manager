<?php

namespace App\Entity\Infos;

use App\Entity\Traits\TraitDescription;
use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitSlug;
use App\Entity\Versions\VersionGroup;
use App\Repository\Infos\AbilityVersionGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=AbilityVersionGroupRepository::class)
 */
class AbilityVersionGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitDescription;

    use TraitLanguage;

    use TraitSlug;

    /**
     * @var Ability
     * @ORM\ManyToOne(targetEntity="App\Entity\Infos\Ability", inversedBy="abilityVersionGroup")
     * @JoinColumn(name="ability_id")
     */
    private Ability $ability;

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
     * @return Ability
     */
    public function getAbility(): Ability
    {
        return $this->ability;
    }

    /**
     * @param Ability $ability
     * @return AbilityVersionGroup
     */
    public function setAbility(Ability $ability): AbilityVersionGroup
    {
        $this->ability = $ability;
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
     * @return AbilityVersionGroup
     */
    public function setVersionGroup(VersionGroup $versionGroup): AbilityVersionGroup
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }
}
