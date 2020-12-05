<?php

namespace App\Entity\Pokemon;

use App\Repository\PokemonSpeciesVersionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonSpeciesVersionRepository::class)
 */
class PokemonSpeciesVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flavorText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $genera;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlavorText(): ?string
    {
        return $this->flavorText;
    }

    public function setFlavorText(?string $flavorText): self
    {
        $this->flavorText = $flavorText;

        return $this;
    }

    public function getGenera(): ?string
    {
        return $this->genera;
    }

    public function setGenera(?string $genera): self
    {
        $this->genera = $genera;

        return $this;
    }
}
