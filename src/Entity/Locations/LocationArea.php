<?php

namespace App\Entity\Locations;

use App\Entity\Traits\TraitApi;
use App\Entity\Traits\TraitNomenclature;
use App\Repository\Locations\LocationAreaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=LocationAreaRepository::class)
 */
class LocationArea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitApi;

    use TraitNomenclature;

    /**
     * @var Location
     * @ORM\ManyToOne(targetEntity="App\Entity\Locations\Location")
     * @JoinColumn(name="location_id")
     */
    private Location $location;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param Location $location
     * @return LocationArea
     */
    public function setLocation(Location $location): LocationArea
    {
        $this->location = $location;
        return $this;
    }

}
