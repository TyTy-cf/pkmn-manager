<?php


namespace App\Repository\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\AbstractRepository;
use Doctrine\Persistence\ManagerRegistry;

class MoveLearnMethodRepository extends AbstractRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveLearnMethod::class);
    }

    /**
     * @param Language $language
     * @return int|mixed|string
     */
    public function getAllMoveLearnMethodByLanguage(Language $language)
    {
        return $this->createQueryBuilder('mlm')
            ->select('mlm')
            ->join('mlm.language', 'language')
            ->where('language = :language')
            ->setParameter('language', $language)
            ->orderBy('mlm.displayOrder')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param Language $language
     * @param Pokemon $pokemon
     * @return int|mixed|string
     */
    public function getMoveLearnMethodByLanguageAndPokemon(Language $language, Pokemon $pokemon)
    {
        return $this->createQueryBuilder('mlm')
            ->select('mlm')
            ->join('mlm.language', 'language')
            ->join(PokemonMovesLearnVersion::class, 'pmlv', 'WITH', 'pmlv.moveLearnMethod = mlm')
            ->where('language = :language')
            ->andWhere('pmlv.pokemon = :pokemon')
            ->setParameter('language', $language)
            ->setParameter('pokemon', $pokemon)
            ->orderBy('mlm.displayOrder')
            ->getQuery()
            ->getResult()
        ;
    }

}
