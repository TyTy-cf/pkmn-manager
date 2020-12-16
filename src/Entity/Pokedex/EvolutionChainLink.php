<?php


namespace App\Entity\Pokedex;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\EvolutionChainLinkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class EvolutionChainChain
 * @package App\Entity\Pokedex
 * @ORM\Entity(repositoryClass=EvolutionChainLinkRepository::class)
 */
class EvolutionChainLink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var PokemonSpecies $currentPokemonSpecies
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\PokemonSpecies")
     * @JoinColumn(name="from_pokemon_species_id")
     */
    private PokemonSpecies $currentPokemonSpecies;

    /**
     * @var EvolutionDetail|null $evolutionDetail
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionDetail", cascade={"persist"})
     * @JoinColumn(name="evolution_detail_id", nullable=true)
     */
    private ?EvolutionDetail $evolutionDetail;

    /**
     * @var Collection $evolutionsChainLinks
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Pokedex\EvolutionChainLink", cascade={"persist"})
     * @ORM\JoinTable(name="evolution_chain_link_chain",
     *      joinColumns={@JoinColumn(name="evolution_chain_link_from_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="evolution_chain_link_to_id", referencedColumnName="id")}
     * )
     */
    private Collection $evolutionsChainLinks;

    /**
     * @ORM\Column(type="smallint")
     */
    private bool $isBaby;

    /**
     * @ORM\Column(type="integer")
     */
    private int $evolutionOrder;

    /**
     * EvolutionChainLink constructor.
     */
    public function __construct()
    {
        $this->evolutionsChainLinks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isBaby(): bool
    {
        return $this->isBaby;
    }

    /**
     * @param bool $isBaby
     * @return EvolutionChainLink
     */
    public function setIsBaby(bool $isBaby): self
    {
        $this->isBaby = $isBaby;
        return $this;
    }

    /**
     * @return PokemonSpecies
     */
    public function getCurrentPokemonSpecies(): PokemonSpecies
    {
        return $this->currentPokemonSpecies;
    }

    /**
     * @param PokemonSpecies $currentPokemonSpecies
     * @return EvolutionChainLink
     */
    public function setCurrentPokemonSpecies(PokemonSpecies $currentPokemonSpecies): self
    {
        $this->currentPokemonSpecies = $currentPokemonSpecies;
        return $this;
    }

    /**
     * @return EvolutionDetail|null
     */
    public function getEvolutionDetail(): ?EvolutionDetail
    {
        return $this->evolutionDetail;
    }

    /**
     * @param EvolutionDetail|null $evolutionDetail
     * @return EvolutionChainLink
     */
    public function setEvolutionDetail(?EvolutionDetail $evolutionDetail): self
    {
        $this->evolutionDetail = $evolutionDetail;
        return $this;
    }

    /**
     * @return int
     */
    public function getEvolutionOrder(): int
    {
        return $this->evolutionOrder;
    }

    /**
     * @param int $evolutionOrder
     * @return EvolutionChainLink
     */
    public function setEvolutionOrder(int $evolutionOrder): self
    {
        $this->evolutionOrder = $evolutionOrder;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getEvolutionsChainLinks() {
        return $this->evolutionsChainLinks;
    }

    /**
     * @param EvolutionChainLink $evolutionChainLink
     */
    public function addEvolutionChainLink(EvolutionChainLink $evolutionChainLink) {
        if (!$this->evolutionsChainLinks->contains($evolutionChainLink)) {
            $this->evolutionsChainLinks[] = $evolutionChainLink;
        }
    }

    /**
     * @param EvolutionChainLink $evolutionChainLink
     */
    public function removeEvolutionChainLink(EvolutionChainLink $evolutionChainLink) {
        if ($this->evolutionsChainLinks->contains($evolutionChainLink)) {
            $this->evolutionsChainLinks->removeElement($evolutionChainLink);
        }
    }

}
