<?php


namespace App\Repository\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonSheet[]    findAll()
 * @method PokemonSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonSheetRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, PokemonSheet::class);
    }
}
