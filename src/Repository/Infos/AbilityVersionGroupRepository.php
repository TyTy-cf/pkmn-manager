<?php

namespace App\Repository\Infos;

use App\Entity\Infos\Ability;
use App\Entity\Infos\AbilityVersionGroup;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbilityVersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbilityVersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbilityVersionGroup[]    findAll()
 * @method AbilityVersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbilityVersionGroupRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbilityVersionGroup::class);
    }

    /**
     * @param string $slug
     * @return int|mixed|string
     */
    public function findAbilityVersionGroupBySlug(string $slug) {
        return $this->createQueryBuilder('ability_version_group')
            ->select('ability_version_group', 'version_group')
            ->join('ability_version_group.versionGroup', 'version_group')
            ->join('ability_version_group.ability', 'ability')
            ->where('ability.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('version_group.displayedOrder', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findLastDescriptionByAbility(Ability $ability): AbilityVersionGroup
    {
        return $this->createQueryBuilder('ability_version_group')
            ->select('ability_version_group')
            ->join('ability_version_group.versionGroup', 'version_group')
            ->join('ability_version_group.ability', 'ability')
            ->where('ability = :ability')
            ->setParameter('ability', $ability)
            ->orderBy('version_group.displayedOrder', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()[0]
        ;
    }
}
