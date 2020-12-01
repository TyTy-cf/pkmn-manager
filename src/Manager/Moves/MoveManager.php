<?php

namespace App\Manager\Moves;

use App\Entity\Infos\Type\Type;
use App\Entity\Moves\DamageClass;
use App\Entity\Moves\Move;
use App\Entity\Users\Language;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\TextManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class MoveManager
{
    /**
     * @var MoveRepository
     */
    private $movesRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var ApiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TypeManager
     */
    private TypeManager $typeManager;

    /**
     * @var TextManager $textManager
     */
    private TextManager $textManager;

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
     */
    public function __construct(
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TypeManager $typeManager,
        TextManager $textManager,
        DamageClassManager $damageClassManager,
        MoveDescriptionManager $moveDescriptionManager
    )
    {
        $this->em = $em;
        $this->apiManager = $apiManager;
        $this->movesRepository = $this->em->getRepository(Move::class);
        $this->typeManager = $typeManager;
        $this->textManager = $textManager;
        $this->damageClassManager = $damageClassManager;
        $this->moveDescriptionManager = $moveDescriptionManager;
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Move|null
     * @throws NonUniqueResultException
     */
    private function getMoveByLanguageAndSlug(Language $language, string $slug)
    {
        return $this->movesRepository->getMoveByLanguageAndSlug(
            $language,
            $slug
        );
    }

    /**
     * If not exist, save the moves in database
     * @param Language $lang
     * @param $apiResponse
     * @throws NonUniqueResultException
     */
    public function saveMove(Language $lang, array $apiResponse)
    {
        $name = $apiResponse['name'];
        $slug = $this->textManager->generateSlugFromClass(Move::class, $name);

        if ($this->getMoveByLanguageAndSlug($lang, $slug) !== null)
        {
            $moveName = $this->apiManager->getNameBasedOnLanguageFromArray($lang->getCode(), $apiResponse);
            // Get the DamageClass
            $damageClass = $this->damageClassManager->getDamageClassByLanguageAndSlug(
                $lang,
                $this->textManager->generateSlugFromClass(DamageClass::class, $name)
            );
            // Get the Type
            $type = $this->typeManager->getTypeByLanguageAndSlug(
                $lang,
                $this->textManager->generateSlugFromClass(Type::class, $apiResponse['type']['name'])
            );

            // Create the Move
            $move = new Move();
            $move->setType($type);
            $move->setSlug($slug);
            $move->setName($moveName);
            $move->setLanguage($lang);
            $move->setPp($apiResponse['pp']);
            $move->setDamageClass($damageClass);
            $move->setPower($apiResponse['power']);
            $move->setPriority($apiResponse['priority']);
            $move->setAccuracy($apiResponse['accuracy']);
            $this->em->persist($move);

            // Create the MoveDescription
            $this->moveDescriptionManager->createMoveDescription($lang, $move, $name, $apiResponse['flavor_text_entries']);

            $this->em->flush();
        }
    }

}