<?php

namespace App\Repository\Pokemon;

use App\Entity\Pokemon\PokemonForm;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonForm[]    findAll()
 * @method PokemonForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonFormRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonForm::class);
    }
}
