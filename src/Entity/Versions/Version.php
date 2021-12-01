<?php


namespace App\Entity\Versions;


use App\Entity\Traits\TraitApi;
use App\Entity\Traits\TraitDisable;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Class Version
 * @package App\Entity\Versions
 * @Entity(repositoryClass="App\Repository\Versions\VersionRepository")
 * @ORM\Table(name="version")
 */
class Version
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string $logo
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private string $logo;

    use TraitDisable;
    use TraitNomenclature;

    /**
     * @var VersionGroup
     *
     * @ManyToOne(targetEntity="App\Entity\Versions\VersionGroup", inversedBy="versions")
     * @JoinColumn(name="version_group_id", referencedColumnName="id")
     */
    private VersionGroup $versionGroup;

    /**
     * Version constructor.
     */
    public function __construct()
    {
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
     * @return string
     */
    public function getLogo(): string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     * @return Version
     */
    public function setLogo(string $logo): Version
    {
        $this->logo = $logo;
        return $this;
    }


}
