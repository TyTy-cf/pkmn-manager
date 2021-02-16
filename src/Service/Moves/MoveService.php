<?php

namespace App\Service\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Moves\DamageClass;
use App\Entity\Moves\Move;
use App\Entity\Users\Language;
use App\Repository\Infos\Type\TypeRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Infos\Type\TypeService;
use App\Service\TextService;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveService extends AbstractService
{
    /**
     * @var MoveRepository $movesRepository
     */
    private MoveRepository $movesRepository;

    /**
     * @var TypeService
     */
    private TypeService $typeManager;

    /**
     * @var DamageClassService $damageClassManager
     */
    private DamageClassService $damageClassManager;

    /**
     * @var MoveDescriptionService
     */
    private MoveDescriptionService $moveDescriptionManager;

    /**
     * @var TypeRepository $typeRepository
     */
    private TypeRepository $typeRepository;

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiManager
     * @param TextService $textService
     * @param DamageClassService $damageClassService
     * @param MoveDescriptionService $moveDescriptionManager
     * @param MoveRepository $moveRepository
     * @param TypeRepository $typeRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiManager,
        TextService $textService,
        DamageClassService $damageClassService,
        MoveDescriptionService $moveDescriptionManager,
        MoveRepository $moveRepository,
        TypeRepository $typeRepository
    )
    {
        $this->movesRepository = $moveRepository;
        $this->typeRepository = $typeRepository;
        $this->damageClassManager = $damageClassService;
        $this->moveDescriptionManager = $moveDescriptionManager;
        parent::__construct($em, $apiManager, $textService);
    }

    /**
     * @param string $slug
     * @return Move|null
     */
    public function getMoveBySlug(string $slug): ?Move
    {
        return $this->movesRepository->findOneBySlug($slug);
    }

    /**
     * @param string $slug
     * @return Move|null
     * @throws NonUniqueResultException
     */
    public function getSimpleMoveBySlug(string $slug): ?Move {
        return $this->movesRepository->getSimpleMoveBySlug($slug);
    }

    /**
     * If not exist, save the moves in database
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Move::class, $apiResponse['name']
        );
        $urlDetailedMove = $this->apiManager->apiConnect($apiResponse['url'])->toArray();

        if ($this->getMoveBySlug($slug) === null && isset($urlDetailedMove['damage_class']))
        {;
            $moveName = $this->apiManager->getNameBasedOnLanguageFromArray(
                $language->getCode(),
                $urlDetailedMove
            );
            // Get the DamageClass
            $damageClass = $this->damageClassManager->getDamageClassBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language,
                    DamageClass::class,
                    $urlDetailedMove['damage_class']['name']
                )
            );
            // Get the Type
            $type = $this->typeRepository->findOneBySlug(
                $this->textManager->generateSlugFromClassWithLanguage(
                    $language,
                    Type::class,
                    $urlDetailedMove['type']['name']
                )
            );
            if ($urlDetailedMove['pp'] !== null || $urlDetailedMove['power'] !== null || $urlDetailedMove['accuracy'] !== null)
            {
                // Create the Move
                $move = (new Move())
                    ->setType($type)
                    ->setSlug($slug)
                    ->setName($moveName)
                    ->setLanguage($language)
                    ->setPp($urlDetailedMove['pp'])
                    ->setDamageClass($damageClass)
                    ->setPower($urlDetailedMove['power'])
                    ->setPriority($urlDetailedMove['priority'])
                    ->setAccuracy($urlDetailedMove['accuracy'])
                ;

                $this->entityManager->persist($move);

                // Create the MoveDescription
                $this->moveDescriptionManager->createMoveDescription(
                    $language,
                    $move,
                    $urlDetailedMove['flavor_text_entries']
                );
                $this->entityManager->flush();
            }
        }
    }

}