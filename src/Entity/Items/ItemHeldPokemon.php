<?php

namespace App\Entity\Items;

use App\Entity\Pokemon\Pokemon;
use App\Repository\Items\ItemHeldPokemonRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=ItemHeldPokemonRepository::class)
 * @ORM\Table(name="item_held_pokemon")
 */
class ItemHeldPokemon
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Item $item
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="item_id")
     */
    private Item $item;

    /**
     * @var Pokemon $pokemon
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon")
     * @JoinColumn(name="pokemon_id")
     */
    private Pokemon $pokemon;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return ItemHeldPokemon
     */
    public function setItem(Item $item): self
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return Pokemon
     */
    public function getPokemon(): Pokemon
    {
        return $this->pokemon;
    }

    /**
     * @param Pokemon $pokemon
     * @return ItemHeldPokemon
     */
    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

}
