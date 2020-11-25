<?php


namespace App\Repository\Infos;

use App\Entity\Infos\TypeDamageFromType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeDamageFromType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDamageFromType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDamageFromType[]    findAll()
 * @method TypeDamageFromType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDamageFromTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDamageFromType::class);
    }

}