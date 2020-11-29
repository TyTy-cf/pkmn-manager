<?php


namespace App\Entity\Pokemon;


use App\Entity\Versions\Version;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\Version")
     * @JoinColumn(name="version_id", referencedColumnName="id")
     */
    private Version $version;

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
     */
    public function setPokemon(Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
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
     */
    public function setVersion(Version $version): void
    {
        $this->version = $version;
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
     */
    public function setUrlDefault(?string $urlDefault): void
    {
        $this->urlDefault = $urlDefault;
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
     */
    public function setUrlDefaultShiny(?string $urlDefaultShiny): void
    {
        $this->urlDefaultShiny = $urlDefaultShiny;
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
     */
    public function setUrlDefaultFemale(?string $urlDefaultFemale): void
    {
        $this->urlDefaultFemale = $urlDefaultFemale;
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
     */
    public function setUrlDefaultFemaleShiny(?string $urlDefaultFemaleShiny): void
    {
        $this->urlDefaultFemaleShiny = $urlDefaultFemaleShiny;
    }

}