<?php

namespace App\Entity\Pokedex;

use App\Entity\Items\Item;
use App\Entity\Traits\TraitSlug;
use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=EvolutionChainRepository::class)
 */
class EvolutionChain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitSlug;

    /**
     * @var EvolutionChainLink $evolutionChainLink
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionChainLink")
     * @JoinColumn(name="evolution_chain_link_id", nullable=true)
     */
    private EvolutionChainLink $evolutionChainLink;

    /**
     * @var Item|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="baby_item_trigger_id", nullable=true)
     */
    private ?Item $babyItemTrigger;

    /**
     * @ORM\Column(type="integer")
     */
    private int $idApi;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return EvolutionChainLink
     */
    public function getEvolutionChainLink(): EvolutionChainLink
    {
        return $this->evolutionChainLink;
    }

    /**
     * @param EvolutionChainLink $evolutionChainLink
     * @return EvolutionChain
     */
    public function setEvolutionChainLink(EvolutionChainLink $evolutionChainLink): EvolutionChain
    {
        $this->evolutionChainLink = $evolutionChainLink;
        return $this;
    }

    /**
     * @return Item
     */
    public function getBabyItemTrigger(): Item
    {
        return $this->babyItemTrigger;
    }

    /**
     * @param Item|null $babyItemTrigger
     * @return EvolutionChain
     */
    public function setBabyItemTrigger(?Item $babyItemTrigger): self
    {
        $this->babyItemTrigger = $babyItemTrigger;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdApi(): int
    {
        return $this->idApi;
    }

    /**
     * @param int $idApi
     * @return EvolutionChain
     */
    public function setIdApi(int $idApi): EvolutionChain
    {
        $this->idApi = $idApi;
        return $this;
    }
}
