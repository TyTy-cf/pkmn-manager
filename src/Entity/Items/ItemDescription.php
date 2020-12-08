<?php


namespace App\Entity\Items;


use App\Repository\Items\ItemDescriptionRepository;
use App\Entity\Traits\TraitDescription;
use App\Entity\Versions\VersionGroup;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Class ItemDescription
 * @package App\Entity\Items
 * @Entity(repositoryClass=ItemDescriptionRepository::class)
 * @ORM\Table(name="item_description")
 */
class ItemDescription
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitDescription;

    /**
     * @var Item
     * @ORM\ManyToOne(targetEntity="App\Entity\Items\Item")
     * @JoinColumn(name="item_id")
     */
    private Item $item;

    /**
     * @var VersionGroup
     * @ORM\ManyToOne(targetEntity="App\Entity\Versions\VersionGroup")
     * @JoinColumn(name="version_group_id")
     */
    private VersionGroup $versionGroup;

    /**
     * @return int
     */
    public function getId(): int
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
     * @return ItemDescription
     */
    public function setItem(Item $item): ItemDescription
    {
        $this->item = $item;
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
     * @return ItemDescription
     */
    public function setVersionGroup(VersionGroup $versionGroup): ItemDescription
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

}
