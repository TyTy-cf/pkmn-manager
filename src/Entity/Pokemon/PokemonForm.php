<?php

namespace App\Entity\Pokemon;

use App\Entity\Traits\TraitNomenclature;
use App\Repository\Pokemon\PokemonFormRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass=PokemonFormRepository::class)
 */
class PokemonForm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitNomenclature;

    /**
     * @var Pokemon
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon", inversedBy="pokemonForms")
     * @JoinColumn(name="pokemon_id")
     */
    private Pokemon $pokemon;

    /**
     * @var bool $mega
     * @ORM\Column(name="is_mega", type="boolean", nullable=true)
     */
    private bool $mega;

    /**
     * @var bool $default
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private bool $default;

    /**
     * @var bool $battleOnly
     * @ORM\Column(name="is_battle_only", type="boolean", nullable=true)
     */
    private bool $battleOnly;

    /**
     * @ORM\Column(name="form_name", type="string", length=255, nullable=true)
     */
    private ?string $formName;

    /**
     * @ORM\Column(name="form_sprite", type="string", length=255, nullable=true)
     */
    private ?string $formSprite;

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return PokemonForm
     */
    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMega(): bool
    {
        return $this->mega;
    }

    /**
     * @param bool $mega
     * @return PokemonForm
     */
    public function setMega(bool $mega): self
    {
        $this->mega = $mega;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return PokemonForm
     */
    public function setDefault(bool $default): self
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBattleOnly(): bool
    {
        return $this->battleOnly;
    }

    /**
     * @param bool $battleOnly
     * @return PokemonForm
     */
    public function setBattleOnly(bool $battleOnly): self
    {
        $this->battleOnly = $battleOnly;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormName(): ?string
    {
        return $this->formName;
    }

    /**
     * @param string|null $formName
     * @return PokemonForm
     */
    public function setFormName(?string $formName): self
    {
        $this->formName = $formName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormSprite(): ?string
    {
        return $this->formSprite;
    }

    /**
     * @param string|null $formSprite
     * @return PokemonForm
     */
    public function setFormSprite(?string $formSprite): self
    {
        $this->formSprite = $formSprite;
        return $this;
    }

}
