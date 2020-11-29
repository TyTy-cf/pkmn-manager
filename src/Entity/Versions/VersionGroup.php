<?php

namespace App\Entity\Versions;

use App\Entity\Traits\TraitLanguage;
use App\Entity\Traits\TraitNames;
use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Versions\VersionGroupRepository")
 * @ORM\Table(name="version_group")
 */
class VersionGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNames;

    use TraitSlug;

    use TraitLanguage;

    /**
     * @var Generation $generation
     *
     * @ManyToOne(targetEntity="App\Entity\Versions\Generation")
     * @JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private Generation $generation;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Generation
     */
    public function getGeneration(): Generation
    {
        return $this->generation;
    }

    /**
     * @param Generation $generation
     */
    public function setGeneration(Generation $generation): void
    {
        $this->generation = $generation;
    }

}
