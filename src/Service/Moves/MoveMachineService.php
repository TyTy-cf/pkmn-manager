<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveMachine;
use App\Entity\Users\Language;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class MoveMachineService
 * @package App\Service\Moves
 *
 * @property MoveMachineRepository $moveMachineRepository
 * @property VersionGroupService $versionGroupManager
 * @property MoveRepository $movesRepository
 */
class MoveMachineService extends AbstractService
{

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiService
     * @param TextService $textService
     * @param MoveRepository $movesRepository
     * @param VersionGroupService $versionGroupService
     * @param MoveMachineRepository $moveMachineRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        ApiService $apiService,
        TextService $textService,
        MoveRepository $movesRepository,
        VersionGroupService $versionGroupService,
        MoveMachineRepository $moveMachineRepository
    ) {
        $this->moveMachineRepository = $moveMachineRepository;
        $this->movesRepository = $movesRepository;
        $this->versionGroupManager = $versionGroupService;
        parent::__construct($em, $apiService, $textService);
    }

    /**
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $urlDetailed = $this->apiService->apiConnect($apiResponse['url'])->toArray();
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, MoveMachine::class, $urlDetailed['item']['name'].'-version-group-'.$urlDetailed['version_group']['name']
        );
        $isNew = false;
        if (null === $moveMachine = $this->moveMachineRepository->findOneBySlug($slug)) {
            $moveMachine = new MoveMachine();
            $isNew = true;
        }

        $codeLanguage = $language->getCode();
        $moveNameLang = $this->apiService->getNameBasedOnLanguage($codeLanguage, $urlDetailed['move']['url']);
        $slugMove= $codeLanguage. '-' . $this->textService->slugify($moveNameLang);
        $move = $this->movesRepository->findOneBySlug($slugMove);

        if (null !== $move)
        {
            $groupVersion = $this->versionGroupManager->getVersionGroupBySlug(
                $this->textService->generateSlugFromClassWithLanguage(
                    $language, VersionGroup::class, $urlDetailed['version_group']['name']
                )
            );
            if (null !== $groupVersion) {
                $urlDetailedItem = $this->apiService->apiConnect($urlDetailed['item']['url'])->toArray();
                $name = $this->apiService->getNameBasedOnLanguageFromArray($codeLanguage, $urlDetailedItem);
                $slug = $codeLanguage . '-' . $urlDetailed['item']['name'] . '-' . $urlDetailed['version_group']['name'];
                $moveMachine
                    ->setVersionGroup($groupVersion)
                    ->setName($name)
                    ->setNumber($this->getMachineNumberFromName($name))
                    ->setMove($move)
                    ->setCost($urlDetailedItem['cost'])
                    ->setSlug($slug)
                ;
                if (isset($urlDetailedItem['sprites']['default']))
                {
                    $moveMachine->setImageUrl($urlDetailedItem['sprites']['default']);
                }
                // Fetch the description
                foreach($urlDetailedItem['flavor_text_entries'] as $flavorTextEntry)
                {
                    $slugVersion = $this->textService->generateSlugFromClassWithLanguage(
                        $language,
                        VersionGroup::class,
                        $flavorTextEntry['version_group']['name']
                    );
                    if ($flavorTextEntry['language']['name'] === $language->getCode()
                     && $slugVersion === $groupVersion->getSlug())
                    {
                        $moveMachine->setDescription(
                            $this->textService->removeReturnLineFromText($flavorTextEntry['text'])
                        );
                        break;
                    }
                }

                if ($isNew) {
                    $moveMachine->setLanguage($language);
                    $this->entityManager->persist($moveMachine);
                }
            }
        }
    }

    /**
     * Substring the machine's name to get the number, in order to improve the order
     * For CS the number would be something like 1003 to make sure they are at the end
     * @param string $name
     * @return false|int|string
     */
    public function getMachineNumberFromName(string $name) {
        $number = substr($name, 2, strlen($name));
        if (false !== strpos($name, 'CS')) {
            $number = $number + 1000;
        }
        return $number;
    }

}
