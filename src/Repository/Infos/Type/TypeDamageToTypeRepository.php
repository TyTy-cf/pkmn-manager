<?php


namespace App\Repository\Infos\Type;


use App\Entity\Infos\Type\TypeDamageToType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeDamageToType|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeDamageToType|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeDamageToType[]    findAll()
 * @method TypeDamageToType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeDamageToTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDamageToType::class);
    }

}