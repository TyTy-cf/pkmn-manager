<?php


namespace App\Manager\Moves;


use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\PokemonMovesLearnVersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonMovesLearnVersionManager extends AbstractManager
{

    /**
     * @var MoveManager $moveManager
     */
    private MoveManager $moveManager;

    /**
     * @var PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    private PokemonMovesLearnVersionRepository $repoPokemonMoves;

    /**
     * @var MoveLearnMethodManager $moveLearnMethodManager
     */
    private MoveLearnMethodManager $moveLearnMethodManager;

    /**
     * MoveManager constructor
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param MoveManager $moveManager
     * @param MoveLearnMethodManager $moveLearnMethodManager
     * @param PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TextManager $textManager,
        MoveManager $moveManager,
        MoveLearnMethodManager $moveLearnMethodManager,
        PokemonMovesLearnVersionRepository $repoPokemonMoves
    )
    {
        $this->moveManager = $moveManager;
        $this->moveLearnMethodManager = $moveLearnMethodManager;
        $this->repoPokemonMoves = $repoPokemonMoves;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return PokemonMovesLearnVersion|null
     * @throws NonUniqueResultException
     */
    public function getPokemonMovesLearnVersionBySlug(string $slug): ?PokemonMovesLearnVersion
    {
        return $this->repoPokemonMoves->getPokemonMovesLearnVersionByLanguageAndSlug($slug);
    }

    /**
     * @return int|mixed|string
     */
    public function getLastPokemonIdInDataBase()
    {
        return $this->repoPokemonMoves->getLastPokemonIdInDataBase();
    }

    /**
     * @param Language $language
     * @param Pokemon $pokemon
     * @param array $arrayGroupVersion
     * @param array $arrayMoveLearnMethod
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    public function createMoveFromApiResponse
    (
        Language $language,
        Pokemon $pokemon,
        array $arrayGroupVersion,
        array $arrayMoveLearnMethod
    )
    {
        $urlDetailed = json_decode(
            $this->apiManager->getPokemonFromName($pokemon->getNameApi())->getContent(),
            true
        );

        // Fetchs all moves
        foreach ($urlDetailed['moves'] as $detailedMove)
        {
            // Fetch the move from data
            $moveName = $detailedMove['move']['name'];
            $move = $this->moveManager->getMoveByLanguageAndSlug(
                $language,
                'move-'.$moveName
            );
            foreach ($detailedMove['version_group_details'] as $detailGroup)
            {
                $versionGroupName = $detailGroup['version_group']['name'];
                $versionGroup = $arrayGroupVersion[$versionGroupName];
                $moveLearnMethodName = $detailGroup['move_learn_method']['name'];
                if (isset($arrayMoveLearnMethod['move-learn-method-'.$moveLearnMethodName]))
                {
                    $moveLearnMethod = $arrayMoveLearnMethod['move-learn-method-'.$moveLearnMethodName];
                    // Slug
                    $slug = $language->getCode().'-'.
                        $pokemon->getNameApi().'-'.
                        $moveName.'-'.
                        $moveLearnMethodName.'-'.
                        $versionGroupName;

                    if (($pokemonMoveLearn = $this->getPokemonMovesLearnVersionBySlug($slug)) === null)
                    {
                        $pokemonMoveLearn = new PokemonMovesLearnVersion();
                        $pokemonMoveLearn->setMove($move);
                        $pokemonMoveLearn->setPokemon($pokemon);
                        $pokemonMoveLearn->setVersionGroup($versionGroup);
                        $pokemonMoveLearn->setMoveLearnMethod($moveLearnMethod);
                        $pokemonMoveLearn->setLevel($detailGroup['level_learned_at']);
                        $pokemonMoveLearn->setSlug($slug);
                    } else {
                        if ($pokemonMoveLearn->getLevel() !== $detailGroup['level_learned_at'])
                        {
                            $pokemonMoveLearn->setLevel($detailGroup['level_learned_at']);
                        }
                    }
                    $this->entityManager->persist($pokemonMoveLearn);
                    $this->entityManager->flush();

                }
            }
        }
    }

}