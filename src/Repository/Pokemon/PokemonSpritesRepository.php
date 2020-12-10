<?php


namespace App\Repository\Pokemon;


use App\Entity\Pokemon\PokemonSprites;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSprites|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSprites|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSprites[]    findAll()
 * @method PokemonSprites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSpritesRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonSprites::class);
    }

}
