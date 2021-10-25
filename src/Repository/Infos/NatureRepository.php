<?php


namespace App\Repository\Infos;


use App\Entity\Infos\Nature;
use App\Repository\AbstractRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nature::class);
    }

    /**
     * @return Query
     */
    public function queryAll(): Query
    {
        return $this->createQueryBuilder('nature')
            ->orderBy('nature.name', 'ASC')
            ->getQuery()
        ;
    }

    /**
     * @param string $code
     * @return Nature[]
     */
    public function findAllByLanguageCode(string $code): array {
        return $this->createQueryBuilder('nature')
            ->select('nature')
            ->join('nature.language', 'language')
            ->where('language.code = :code')
            ->setParameter('code', $code)
            ->orderBy('nature.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
