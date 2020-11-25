<?php


namespace App\Entity\Users;


use App\Entity\Infos\Abilities;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Users\LanguageRepository")
 */
class Language
{

    /**
     * @var int $id
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", length=6)
     */
    private int $id;

    /**
     * @var string $code
     * @ORM\Column(name="code", type="string", length=3)
     */
    private string $code;

    /**
     * @var string $title
     * @ORM\Column(name="title", type="string", length=255)
     */
    private string $title;

    /**
     * @var string $img
     * @ORM\Column(name="img", type="string", length=255, nullable=true)
     */
    private string $img;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users\User", mappedBy="language")
     * @var Collection
     */
    private Collection $users;

    /**
     * @ORM\OneToMany(targetEntity=Abilities::class, mappedBy="languageId")
     */
    private $abilities;

    /**
     * Language constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->abilities = new ArrayCollection();
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this->img = $img;
    }

    /**
     * @return Collection
     */
    public function getUsers() : Collection
    {
        return $this->users;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user): void
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }
    }

    /**
     * @param User $user
     */
    public function removeUser(User $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }
    }

    /**
     * @return Collection|Abilities[]
     */
    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    public function addAbility(Abilities $ability): self
    {
        if (!$this->abilities->contains($ability)) {
            $this->abilities[] = $ability;
            $ability->setLanguageId($this);
        }

        return $this;
    }

    public function removeAbility(Abilities $ability): self
    {
        if ($this->abilities->removeElement($ability)) {
            // set the owning side to null (unless already changed)
            if ($ability->getLanguageId() === $this) {
                $ability->setLanguageId(null);
            }
        }

        return $this;
    }
}