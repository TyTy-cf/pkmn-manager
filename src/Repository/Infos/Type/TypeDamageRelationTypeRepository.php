<?php


namespace App\Repository\Infos\Type;


use App\Entity\Infos\Type\Type;
use App\Entity\Infos\Type\TypeDamageRelationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TypeDamageRelationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDamageRelationType::class);
    }

    /**
     * @param Type $type
     * @return int|mixed|string
     */
    public function getRelationTypeByType(Type $type)
    {
        return $this->createQueryBuilder('type_damage_relation_type')
            ->select('type_damage_relation_type')
            ->join('type_damage_relation_type.type', 'type')
            ->where('type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult()
        ;
    }

}