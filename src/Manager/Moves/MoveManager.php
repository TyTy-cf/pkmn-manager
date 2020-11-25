<?php

namespace App\Manager\Moves;

use App\Entity\Moves\Move;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\TypeManager;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * MoveManager constructor
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param TypeManager $typeManager
     */
    public function __construct(EntityManagerInterface $em, ApiManager $apiManager, TypeManager $typeManager)
    {
        $this->em = $em;
        $this->apiManager = $apiManager;
        $this->movesRepository = $this->em->getRepository(Move::class);
        $this->typeManager = $typeManager;
    }

    /**
     * If not exist, save the moves in database
     * @param $language
     * @param string $lang
     * @param $apiResponse
     */
    public function saveMove($language, string $lang, array $apiResponse)
    {
        foreach ($apiResponse as $move) {
            $moveUrl = $move['move']['url'];
            $movesResponse = $this->apiManager->getDetailed($moveUrl);
            $movesResponse = $movesResponse->toarray();


            foreach ($movesResponse['names'] as $moveName) {

                if ($moveNameLang = $moveName['language']['name'] === $lang) {
                    if ($this->movesRepository->findOneBy(['name' => $moveNameLang]) === null) {
                        $newMove = new Move();

                        $newMove->setPp($movesResponse['pp']);
                        $newMove->setAccuracy($movesResponse['accuracy']);
                        $newMove->setPower($movesResponse['power']);
                        $newMove->setPriority($movesResponse['priority']);
                        $newMove->setName($moveNameLang);
                        $newMove->setLanguage($language);

                        foreach ($movesResponse['flavor_text_entries'] as $moveDescription) {
                            if ($moveDescription['language']['name'] === $lang) {
                                $newMove->setDescription($moveDescription['flavor_text']);
                                break;
                            }
                        }

                        dump($newMove);
                        die();


                    }
                }

            }


        }
    }

}