<?php

namespace App\Repository\Infos;

use App\Entity\Infos\Ability;
use App\Entity\Users\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ability|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ability|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ability[]    findAll()
 * @method Ability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ability::class);
    }

    /**
     * @param Language $language
     * @param string $slug
     * @return Ability|null
     * @throws NonUniqueResultException
     */
    public function getAbilitiesByLanguageAndSlug(Language $language, string $slug): ?Ability
    {
        $qb = $this->createQueryBuilder('abilities');
        $qb->join('abilities.language', 'language');
        $qb->where('language = :lang');
        $qb->andWhere('abilities.slug = :slug');
        $qb->setParameter('lang', $language);
        $qb->setParameter('slug', $slug);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
