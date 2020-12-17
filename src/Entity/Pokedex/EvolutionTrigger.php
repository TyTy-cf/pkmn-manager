<?php

namespace App\Entity\Pokedex;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Pokedex\EvolutionTriggerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvolutionTriggerRepository::class)
 */
class EvolutionTrigger
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @ORM\Column(type="string")
     */
    private string $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return EvolutionTrigger
     */
    public function setTitle(string $title): EvolutionTrigger
    {
        $this->title = $title;
        return $this;
    }

}
