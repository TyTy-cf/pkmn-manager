<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveMachine;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class MoveMachineRepository extends AbstractRepository
{
    /**
     * MoveMachineRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveMachine::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return MoveMachine|null
     * @throws NonUniqueResultException
     */
    public function getMoveMachineByLanguageAndSlug(Language $language, string $slug): ?MoveMachine
    {
        $qb = $this->createQueryBuilder('move_machine');
        $qb->where('move_machine.language = :lang');
        $qb->andWhere('move_machine.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}