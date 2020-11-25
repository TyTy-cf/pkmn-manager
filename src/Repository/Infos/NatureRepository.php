<?php


namespace App\Repository\Infos;


use App\Entity\Infos\Nature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Nature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nature[]    findAll()
 * @method Nature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nature::class);
    }

}