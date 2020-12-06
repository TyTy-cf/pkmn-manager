<?php

namespace App\Repository\Infos\Type;

use App\Entity\Infos\Type\Type;
use App\Repository\AbstractRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Type|null find($id, $lockMode = null, $lockVersion = null)
 * @method Type|null findOneBy(array $criteria, array $orderBy = null)
 * @method Type[]    findAll()
 * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRepository extends AbstractRepository
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
        return $this->createQueryBuilder('type')
            ->join('type.language', 'language')
            ->where('language.code = :lang')
            ->setParameter('lang', $lang)
            ->getQuery()->getResult();
    }

    /**
     * @param string $lang
     * @param $codeApi
     * @return Type|null
     * @throws NonUniqueResultException
     */
    public function getTypeByLanguageAndCodeApi(string $lang, $codeApi): ?Type
    {
        return $this->createQueryBuilder('type')
            ->join('type.language', 'language')
            ->where('language.code = :lang')
            ->andWhere('type.codeApi = :codeApi')
            ->setParameter('lang', $lang)
            ->setParameter('codeApi', $codeApi)
            ->getQuery()->getOneOrNullResult();
    }

}
