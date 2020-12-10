<?php


namespace App\Entity\Users;


use App\Entity\Pokemon\PokemonSheet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Users\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int $id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @var string $nickname
     *
     * @ORM\Column(name="nickname", type="string", length=255)
     */
    private string $nickname;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private string $password;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private string $email;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Pokemon\PokemonSheet", mappedBy="user")
     */
    private Collection $pokemonsSheet;

    /**
     * @var Language $language
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Users\Language", inversedBy="users")
     * @JoinColumn(name="language_id", referencedColumnName="id")
     */
    private Language $language;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->pokemonsSheet = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @param Language $language
     */
    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @return Collection
     */
    public function getPokemonsSheet() : Collection
    {
        return $this->pokemonsSheet;
    }

    /**
     * @param PokemonSheet $pokemonsSheet
     */
    public function addPokemonSheet(PokemonSheet $pokemonsSheet): void
    {
        if (!$this->pokemonsSheet->contains($pokemonsSheet)) {
            $this->pokemonsSheet->add($pokemonsSheet);
        }
    }

    /**
     * @param PokemonSheet $pokemonsSheet
     */
    public function removePokemonSheet(PokemonSheet $pokemonsSheet): void
    {
        if ($this->pokemonsSheet->contains($pokemonsSheet)) {
            $this->pokemonsSheet->removeElement($pokemonsSheet);
        }
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function addPokemonsSheet(PokemonSheet $pokemonsSheet): self
    {
        if (!$this->pokemonsSheet->contains($pokemonsSheet)) {
            $this->pokemonsSheet[] = $pokemonsSheet;
            $pokemonsSheet->setUser($this);
        }

        return $this;
    }

    public function removePokemonsSheet(PokemonSheet $pokemonsSheet): self
    {
        if ($this->pokemonsSheet->removeElement($pokemonsSheet)) {
            // set the owning side to null (unless already changed)
            if ($pokemonsSheet->getUser() === $this) {
                $pokemonsSheet->setUser(null);
            }
        }

        return $this;
    }
}