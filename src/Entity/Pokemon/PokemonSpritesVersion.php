<?php


namespace App\Entity\Pokemon;


use App\Entity\Versions\VersionGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class PokemonSpritesVersion
 * @package App\Entity\Pokemon
 * @ORM\Entity(repositoryClass="App\Repository\Pokemon\PokemonSpritesVersionRepository")
 * @ORM\Table(name="pokemon_sprites_version")
 */
class PokemonSpritesVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pokemon\Pokemon")
     * @JoinColumn(name="pokemon_id", referencedColumnName="id")
     */
    private Pokemon $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinColumn(name="version_group_id", referencedColumnName="id")
     */
    private VersionGroup $versionGroup;

    /**
     * @var string|null
     * @ORM\Column(name="url_default", type="string", length=255, nullable=true)
     */
    private ?string $urlDefault;

    /**
     * @var string|null
     * @ORM\Column(name="url_default_shiny", type="string", length=255, nullable=true)
     */
    private ?string $urlDefaultShiny;

    /**
     * @var string|null
     * @ORM\Column(name="url_default_female", type="string", length=255, nullable=true)
     */
    private ?string $urlDefaultFemale;

    /**
     * @var string|null
     * @ORM\Column(name="url_default_female_shiny", type="string", length=255, nullable=true)
     */
    private ?string $urlDefaultFemaleShiny;

    /**
     * @return int
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
     * @return PokemonSpritesVersion
     */
    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    /**
     * @return VersionGroup
     */
    public function getVersionGroup(): VersionGroup
    {
        return $this->versionGroup;
    }

    /**
     * @param VersionGroup $versionGroup
     * @return PokemonSpritesVersion
     */
    public function setVersionGroup(VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlDefault(): ?string
    {
        return $this->urlDefault;
    }

    /**
     * @param string|null $urlDefault
     * @return PokemonSpritesVersion
     */
    public function setUrlDefault(?string $urlDefault): self
    {
        $this->urlDefault = $urlDefault;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlDefaultShiny(): ?string
    {
        return $this->urlDefaultShiny;
    }

    /**
     * @param string|null $urlDefaultShiny
     * @return PokemonSpritesVersion
     */
    public function setUrlDefaultShiny(?string $urlDefaultShiny): self
    {
        $this->urlDefaultShiny = $urlDefaultShiny;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlDefaultFemale(): ?string
    {
        return $this->urlDefaultFemale;
    }

    /**
     * @param string|null $urlDefaultFemale
     * @return PokemonSpritesVersion
     */
    public function setUrlDefaultFemale(?string $urlDefaultFemale): self
    {
        $this->urlDefaultFemale = $urlDefaultFemale;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlDefaultFemaleShiny(): ?string
    {
        return $this->urlDefaultFemaleShiny;
    }

    /**
     * @param string|null $urlDefaultFemaleShiny
     * @return PokemonSpritesVersion
     */
    public function setUrlDefaultFemaleShiny(?string $urlDefaultFemaleShiny): self
    {
        $this->urlDefaultFemaleShiny = $urlDefaultFemaleShiny;
        return $this;
    }

}