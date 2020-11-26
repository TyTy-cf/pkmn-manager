<?php

namespace App\Repository\Infos\Type;

use App\Entity\Infos\Type\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    /**
     * @param string $lang
     * @return Object[]
     */
    public function getAllTypeByLanguage(string $lang): array
    {
        $qb = $this->createQueryBuilder('type');
        $qb->join('type.language', 'language');
        $qb->where('language.code = :lang');
        $qb->setParameter('lang', $lang);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $lang
     * @param $codeApi
     * @return Type
     * @throws NonUniqueResultException
     */
    public function getTypeByLanguageAndCodeApi(string $lang, $codeApi): Type
    {
        $qb = $this->createQueryBuilder('type');
        $qb->join('type.language', 'language');
        $qb->where('language.code = :lang');
        $qb->andWhere('type.codeApi = :codeApi');
        $qb->setParameter('lang', $lang);
        $qb->setParameter('codeApi', $codeApi);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
