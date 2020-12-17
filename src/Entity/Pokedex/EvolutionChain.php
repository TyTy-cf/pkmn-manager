<?php

namespace App\Entity\Pokedex;

use App\Entity\Items\Item;
use App\Entity\Traits\TraitSlug;
use App\Repository\Pokedex\EvolutionChainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Item|null
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="baby_item_trigger_id", nullable=true)
     */
    private ?Item $babyItemTrigger;

    /**
     * @var Collection $evolutionChainLinks
     *
     * @ORM\OneToMany (targetEntity="App\Entity\Pokedex\EvolutionChainLink", cascade={"persist"}, mappedBy="evolutionChain")
     */
    private Collection $evolutionChainLinks;

    /**
     * @ORM\Column(type="integer")
     */
    private int $idApi;

    /**
     * EvolutionChain constructor.
     */
    public function __construct()
    {
        $this->evolutionChainLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Item|null
     */
    public function getBabyItemTrigger(): ?Item
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

    /**
     * @return ArrayCollection|Collection
     */
    public function getEvolutionChainLinks() {
        return $this->evolutionChainLinks;
    }

    /**
     * @param EvolutionChainLink $evolutionChainLink
     */
    public function addEvolutionChainLinks(EvolutionChainLink $evolutionChainLink) {
        if (!$this->evolutionChainLinks->contains($evolutionChainLink)) {
            $this->evolutionChainLinks[] = $evolutionChainLink;
        }
    }

    /**
     * @param EvolutionChainLink $evolutionChainLink
     */
    public function removeEvolutionChainLinks(EvolutionChainLink $evolutionChainLink) {
        if ($this->evolutionChainLinks->contains($evolutionChainLink)) {
            $this->evolutionChainLinks->removeElement($evolutionChainLink);
        }
    }
}
