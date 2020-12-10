<?php


namespace App\Repository\Infos;


use App\Entity\Infos\PokemonAbility;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonAbility|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonAbility|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonAbility[]    findAll()
 * @method PokemonAbility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonAbilityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonAbility::class);
    }
}
