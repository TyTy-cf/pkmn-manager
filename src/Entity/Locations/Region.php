<?php


namespace App\Entity\Locations;

use App\Entity\Versions\Generation;
use App\Repository\Location\RegionRepository;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Region
 * @package App\Entity\Locations
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @ORM\Table(name="region")
 */
class Region
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Versions\Generation", mappedBy="mainRegion")
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
     * @return Region
     */
    public function setGeneration(Generation $generation): self
    {
        $this->generation = $generation;
        return $this;
    }

}
