<?php


namespace App\Entity\Users;


use App\Entity\Infos\Ability;
use App\Entity\Traits\TraitNames;
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

    use TraitNames;

    /**
     * @var string $code
     * @ORM\Column(name="code", type="string", length=3)
     */
    private string $code;

    /**
     * @var string $image
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private string $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Users\User", mappedBy="language")
     * @var Collection
     */
    private Collection $users;

    /**
     * Language constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
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
     * @return string return the code language
     */
    public function __toString()
    {
        return $this->getCode();
    }
}