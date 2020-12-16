<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveLearnMethodRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveLearnMethod::class);
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllMoveLearnMethodByLanguage(Language $language)
    {
        return $this->createQueryBuilder('mlm')
            ->select('mlm')
            ->join('mlm.language', 'language')
            ->where('language = :language')
            ->setParameter('language', $language)
            ->orderBy('mlm.displayOrder')
            ->getQuery()
            ->getResult()
        ;
    }

}