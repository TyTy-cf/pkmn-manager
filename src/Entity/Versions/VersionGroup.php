<?php

namespace App\Entity\Versions;

use App\Entity\Pokedex\Pokedex;
use App\Entity\Traits\TraitDisable;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    use TraitNomenclature;
    use TraitDisable;

    /**
     * @var string $apiName
     *
     * @ORM\Column(name="api_name", type="string", length=255, nullable=true)
     */
    private string $apiName;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokedex\Pokedex", mappedBy="versionGroup")
     */
    private Collection $pokedex;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Versions\Version", mappedBy="versionGroup")
     * @var Collection
     */
    private Collection $versions;

    /**
     * @var Generation $generation
     *
     * @ManyToOne(targetEntity="App\Entity\Versions\Generation", inversedBy="versionsGroup")
     * @JoinColumn(name="generation_id", referencedColumnName="id")
     */
    private Generation $generation;

    /**
     * @var int $displayedOrder
     *
     * @ORM\Column(name="displayed_order", type="integer", nullable=true)
     */
    private int $displayedOrder;

    /**
     * @var array|string[]
     */
    public static array $avoidList = [
        1 => 'colosseum',
        2 => 'xd',
        3 => 'lets-go',
        4 => 'sword',
        5 => 'shield',
        6 => 'lets-go-pikachu',
        7 => 'lets-go-eevee',
    ];

    /**
     * Pokedex constructor.
     */
    public function __construct()
    {
        $this->pokedex = new ArrayCollection();
        $this->versions = new ArrayCollection();
        $this->isDisable = 0;
    }

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
     * @return VersionGroup
     */
    public function setGeneration(Generation $generation): VersionGroup
    {
        $this->generation = $generation;
        return $this;
    }

    /**
     * @param Version $version
     * @return VersionGroup
     */
    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions->add($version);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    /**
     * @param Version $version
     */
    public function removeVersions(Version $version)
    {
        if ($this->versions->contains($version)) {
            $this->versions->removeElement($version);
        }
    }

    /**
     * @param Pokedex $pokedex
     * @return VersionGroup
     */
    public function addPokedex(Pokedex $pokedex): self
    {
        if (!$this->pokedex->contains($pokedex)) {
            $this->pokedex->add($pokedex);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPokedex(): Collection
    {
        return $this->pokedex;
    }

    /**
     * @param Pokedex $pokedex
     */
    public function removePokedex(Pokedex $pokedex)
    {
        if ($this->pokedex->contains($pokedex)) {
            $this->pokedex->removeElement($pokedex);
        }
    }

    /**
     * @return int
     */
    public function getDisplayedOrder(): int
    {
        return $this->displayedOrder;
    }

    /**
     * @param int $displayedOrder
     * @return VersionGroup
     */
    public function setDisplayedOrder(int $displayedOrder): self
    {
        $this->displayedOrder = $displayedOrder;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiName(): string
    {
        return $this->apiName;
    }

    /**
     * @param string $apiName
     * @return VersionGroup
     */
    public function setApiName(string $apiName): VersionGroup
    {
        $this->apiName = $apiName;
        return $this;
    }

}
