<?php


namespace App\Repository\Infos\Type;


use App\Entity\Infos\Type\TypeDamageRelationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TypeDamageRelationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeDamageRelationType::class);
    }

}