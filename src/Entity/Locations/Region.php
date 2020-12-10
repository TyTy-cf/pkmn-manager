<?php


namespace App\Entity\Locations;

use App\Repository\Location\RegionRepository;
use App\Entity\Traits\TraitNomenclature;
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
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}
