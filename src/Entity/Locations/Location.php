<?php


namespace App\Entity\Locations;


use App\Repository\Locations\LocationRepository;
use App\Entity\Traits\TraitNomenclature;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="App\Entity\Locations\Region")
     * @JoinColumn(name="region_id")
     */
    private Region $region;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Location
     */
    public function setRegion(Region $region): self
    {
        $this->region = $region;
        return $this;
    }
}
