<?php

namespace App\Manager\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Moves\DamageClass;
use App\Entity\Moves\Move;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\TextManager;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveManager extends AbstractManager
{
    /**
     * @var MoveRepository $movesRepository
     */
    private MoveRepository $movesRepository;

    /**
     * @var TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var DamageClassManager $damageClassManager
     */
    private DamageClassManager $damageClassManager;

    /**
     * @var MoveDescriptionManager
     */
    private MoveDescriptionManager $moveDescriptionManager;

    /**
     * MoveManager constructor
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param TypeManager $typeManager
     * @param TextManager $textManager
     * @param DamageClassManager $damageClassManager
     * @param MoveDescriptionManager $moveDescriptionManager
     * @param MoveRepository $moveRepository
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TypeManager $typeManager,
        TextManager $textManager,
        DamageClassManager $damageClassManager,
        MoveDescriptionManager $moveDescriptionManager,
        MoveRepository $moveRepository
    )
    {
        $this->movesRepository = $moveRepository;
        $this->typeManager = $typeManager;
        $this->damageClassManager = $damageClassManager;
        $this->moveDescriptionManager = $moveDescriptionManager;
        parent::__construct($em, $apiManager, $textManager);
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
     * If not exist, save the moves in database
     * @param Language $language
     * @param $apiResponse
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $slug = $this->textManager->generateSlugFromClassWithLanguage(
            $language, Move::class, $apiResponse['name']
        );
        $urlDetailedMove = $this->apiManager->getDetailed($apiResponse['url'])->toArray();

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
            $type = $this->typeManager->getTypeBySlug(
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