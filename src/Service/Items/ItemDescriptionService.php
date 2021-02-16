<?php


namespace App\Service\Items;


use App\Entity\Items\Item;
use App\Entity\Items\ItemDescription;
use App\Entity\Users\Language;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use Doctrine\ORM\EntityManagerInterface;

class ItemDescriptionService extends AbstractService
{

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * AbilitiyManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param VersionGroupService $versionGroupManager
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        ApiService $apiManager,
        TextService $textManager,
        VersionGroupService $versionGroupManager
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
