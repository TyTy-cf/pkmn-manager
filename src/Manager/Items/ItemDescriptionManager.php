<?php


namespace App\Manager\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemDescription;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use Doctrine\ORM\EntityManagerInterface;

class ItemDescriptionManager extends AbstractManager
{

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param VersionGroupManager $versionGroupManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiManager $apiManager,
        TextManager $textManager,
        VersionGroupManager $versionGroupManager
    )
    {
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($entityManager, $apiManager, $textManager);
    }

    /**
     * @param Language $language
     * @param Item $newItem
     * @param $urlDetailed
     */
    public function createItemDescription
    (
        Language $language, Item $newItem, $urlDetailed
    ) {
        // Get all the description
        foreach ($urlDetailed['flavor_text_entries'] as $text) {
            if ($text['language']['name'] === $language->getCode()) {
                $itemDescription = (new ItemDescription())
                    ->setItem($newItem)
                    ->setDescription($this->textManager->removeReturnLineFromText(
                        $text['text']
                    ));
                $arrayVersionGroup = $this->versionGroupManager->getArrayVersionGroup($language);
                $itemDescription->setVersionGroup(
                    $arrayVersionGroup[$language->getCode().'/version-group-'.$text['version_group']['name']]
                );
                $this->entityManager->persist($itemDescription);
            }
        }
    }
}
