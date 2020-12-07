<?php

namespace App\Repository\Versions;

use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionGroup[]    findAll()
 * @method VersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionGroupRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionGroup::class);
    }

    /**
     * @param Language $language
     * @param string $name
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getVersionGroupByLanguageAndName(Language $language, string $name)
    {
        $qb = $this->createQueryBuilder('version_group');
        $qb->where('version_group.language = :lang');
        $qb->andWhere('version_group.name = :name');
        $qb->setParameter('lang', $language);
        $qb->setParameter('name', $name);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
