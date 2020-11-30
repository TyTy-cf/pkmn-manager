<?php


namespace App\Repository\Pokemon;


use App\Entity\Pokemon\PokemonSpritesVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSpritesVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSpritesVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSpritesVersion[]    findAll()
 * @method PokemonSpritesVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpritesVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSpritesVersion::class);
    }
}