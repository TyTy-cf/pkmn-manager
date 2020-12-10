<?php


namespace App\Entity\Pokemon;


use App\Entity\Traits\TraitSlug;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PokemonSprites
 * @package App\Entity\Pokemon
 * @ORM\Entity(repositoryClass="App\Repository\Pokemon\PokemonSpritesRepository")
 * @ORM\Table(name="pokemon_sprites")
 */
class PokemonSprites
{
    /**
     * @var int $id l'id du pkmn en bdd
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    use TraitSlug;

    /**
     * @ORM\Column(name="sprite_icon", type="string", length=255, nullable=true)
     */
    private ?string $spriteIcon;

    /**
     * @ORM\Column(name="sprite_official", type="string", length=255, nullable=true)
     */
    private ?string $spriteOfficial;

    /**
     * @ORM\Column(name="sprite_front_default", type="string", length=255, nullable=true)
     */
    private ?string $spriteFrontDefault;

    /**
     * @ORM\Column(name="sprite_front_shiny", type="string", length=255, nullable=true)
     */
    private ?string $spriteFrontShiny;

    /**
     * @ORM\Column(name="sprite_front_female", type="string", length=255, nullable=true)
     */
    private ?string $spriteFrontFemale;

    /**
     * @ORM\Column(name="sprite_female_shiny", type="string", length=255, nullable=true)
     */
    private ?string $spriteFemaleShiny;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getSpriteIcon(): ?string
    {
        return $this->spriteIcon;
    }

    /**
     * @param string|null $spriteIcon
     * @return PokemonSprites
     */
    public function setSpriteIcon(?string $spriteIcon): self
    {
        $this->spriteIcon = $spriteIcon;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpriteOfficial(): ?string
    {
        return $this->spriteOfficial;
    }

    /**
     * @param string|null $spriteOfficial
     * @return PokemonSprites
     */
    public function setSpriteOfficial(?string $spriteOfficial): self
    {
        $this->spriteOfficial = $spriteOfficial;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpriteFrontDefault(): ?string
    {
        return $this->spriteFrontDefault;
    }

    /**
     * @param string|null $spriteFrontDefault
     * @return PokemonSprites
     */
    public function setSpriteFrontDefault(?string $spriteFrontDefault): self
    {
        $this->spriteFrontDefault = $spriteFrontDefault;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpriteFrontShiny(): ?string
    {
        return $this->spriteFrontShiny;
    }

    /**
     * @param string|null $spriteFrontShiny
     * @return PokemonSprites
     */
    public function setSpriteFrontShiny(?string $spriteFrontShiny): self
    {
        $this->spriteFrontShiny = $spriteFrontShiny;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpriteFrontFemale(): ?string
    {
        return $this->spriteFrontFemale;
    }

    /**
     * @param string|null $spriteFrontFemale
     * @return PokemonSprites
     */
    public function setSpriteFrontFemale(?string $spriteFrontFemale): self
    {
        $this->spriteFrontFemale = $spriteFrontFemale;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpriteFemaleShiny(): ?string
    {
        return $this->spriteFemaleShiny;
    }

    /**
     * @param string|null $spriteFemaleShiny
     * @return PokemonSprites
     */
    public function setSpriteFemaleShiny(?string $spriteFemaleShiny): self
    {
        $this->spriteFemaleShiny = $spriteFemaleShiny;
        return $this;
    }

}
