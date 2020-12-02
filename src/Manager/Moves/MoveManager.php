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
     * @param Language $language
     * @param string $slug
     * @return Move|null
     * @throws NonUniqueResultException
     */
    private function getMoveByLanguageAndSlug(Language $language, string $slug): ?Move
    {
        return $this->movesRepository->getMoveByLanguageAndSlug($language, $slug);
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
        $name = $apiResponse['name'];
        $slug = $this->textManager->generateSlugFromClass(Move::class, $name);

        if ($this->getMoveByLanguageAndSlug($language, $slug) === null && isset($urlDetailedMove['damage_class']))
        {
            $urlDetailedMove = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
            $moveName = $this->apiManager->getNameBasedOnLanguageFromArray($language->getCode(), $urlDetailedMove);
            // Get the DamageClass
            $damageClass = $this->damageClassManager->getDamageClassByLanguageAndSlug(
                $language,
                $this->textManager->generateSlugFromClass(DamageClass::class, $urlDetailedMove['damage_class']['name'])
            );
            // Get the Type
            $type = $this->typeManager->getTypeByLanguageAndSlug(
                $language,
                $this->textManager->generateSlugFromClass(Type::class, $urlDetailedMove['type']['name'])
            );
            if ($urlDetailedMove['pp'] !== null && $urlDetailedMove['power'] && $urlDetailedMove['accuracy'])
            {
                // Create the Move
                $move = new Move();
                $move->setType($type);
                $move->setSlug($slug);
                $move->setName($moveName);
                $move->setLanguage($language);
                $move->setPp($urlDetailedMove['pp']);
                $move->setDamageClass($damageClass);
                $move->setPower($urlDetailedMove['power']);
                $move->setPriority($urlDetailedMove['priority']);
                $move->setAccuracy($urlDetailedMove['accuracy']);
                $this->entityManager->persist($move);

                // Create the MoveDescription
                $this->moveDescriptionManager->createMoveDescription($language, $move, $urlDetailedMove['flavor_text_entries']);

                $this->entityManager->flush();
            }
        }
    }

}