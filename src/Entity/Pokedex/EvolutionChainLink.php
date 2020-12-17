<?php


namespace App\Entity\Pokedex;

use App\Entity\Pokemon\PokemonSpecies;
use App\Repository\Pokedex\EvolutionChainLinkRepository;
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
     * @var EvolutionChain|null $evolutionChain
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokedex\EvolutionChain", inversedBy="evolutionChainLinks")
     * @JoinColumn(name="evolution_chain_id", nullable=true)
     */
    private ?EvolutionChain $evolutionChain;

    /**
     * @ORM\Column(type="smallint")
     */
    private bool $isBaby;

    /**
     * @ORM\Column(type="integer")
     */
    private int $evolutionOrder;

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
    public function setIsBaby(bool $isBaby): EvolutionChainLink
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
    public function setCurrentPokemonSpecies(PokemonSpecies $currentPokemonSpecies): EvolutionChainLink
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
    public function setEvolutionDetail(?EvolutionDetail $evolutionDetail): EvolutionChainLink
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
    public function setEvolutionOrder(int $evolutionOrder): EvolutionChainLink
    {
        $this->evolutionOrder = $evolutionOrder;
        return $this;
    }

    /**
     * @return EvolutionChain|null
     */
    public function getEvolutionChain(): ?EvolutionChain
    {
        return $this->evolutionChain;
    }

    /**
     * @param EvolutionChain|null $evolutionChain
     * @return EvolutionChainLink
     */
    public function setEvolutionChain(?EvolutionChain $evolutionChain): EvolutionChainLink
    {
        $this->evolutionChain = $evolutionChain;
        return $this;
    }

}
