<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class MoveLearnMethodRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveLearnMethod::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return MoveLearnMethod|null
     * @throws NonUniqueResultException
     */
    public function getMoveLearnMethodByLanguageAndSlug(Language $language, string $slug): ?MoveLearnMethod
    {
        $qb = $this->createQueryBuilder('move_learn_method');
        $qb->where('move_learn_method.language = :lang');
        $qb->andWhere('move_learn_method.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }

}