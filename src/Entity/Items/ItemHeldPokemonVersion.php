<?php


namespace App\Entity\Items;


use App\Entity\Versions\Version;
use App\Repository\Items\ItemHeldPokemonVersionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=ItemHeldPokemonVersionRepository::class)
 * @ORM\Table(name="item_held_pokemon_version")
 */
class ItemHeldPokemonVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Version $version
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\Version")
     * @JoinColumn(name="version_id")
     */
    private Version $version;

    /**
     * @var ItemHeldPokemon $itemHeldByPokemon
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\ItemHeldPokemon")
     * @JoinColumn(name="item_held_Pokemon_id")
     */
    private ItemHeldPokemon $itemHeldByPokemon;

    /**
     * @ORM\Column(name="rarity", type="integer", nullable=true)
     */
    private ?int $rarity;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Version
     */
    public function getVersion(): Version
    {
        return $this->version;
    }

    /**
     * @param Version $version
     * @return ItemHeldPokemonVersion
     */
    public function setVersion(Version $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return ItemHeldPokemon
     */
    public function getItemHeldByPokemon(): ItemHeldPokemon
    {
        return $this->itemHeldByPokemon;
    }

    /**
     * @param ItemHeldPokemon $itemHeldByPokemon
     * @return ItemHeldPokemonVersion
     */
    public function setItemHeldByPokemon(ItemHeldPokemon $itemHeldByPokemon): self
    {
        $this->itemHeldByPokemon = $itemHeldByPokemon;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRarity(): ?int
    {
        return $this->rarity;
    }

    /**
     * @param int|null $rarity
     * @return ItemHeldPokemonVersion
     */
    public function setRarity(?int $rarity): self
    {
        $this->rarity = $rarity;
        return $this;
    }

}
