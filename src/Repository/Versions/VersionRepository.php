<?php


namespace App\Repository\Versions;


use App\Entity\Versions\Version;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Version|null find($id, $lockMode = null, $lockVersion = null)
 * @method Version|null findOneBy(array $criteria, array $orderBy = null)
 * @method Version[]    findAll()
 * @method Version[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionRepository  extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Version::class);
    }

    public function findByLanguageCode(string $code)
    {
        return $this->createQueryBuilder('version')
            ->join('version.language', 'language')
            ->where('language.code = :code')
            ->andWhere('version.isDisable = 0')
            ->setParameter('code', $code)
            ->getQuery()
            ->getResult()
        ;
    }

}
