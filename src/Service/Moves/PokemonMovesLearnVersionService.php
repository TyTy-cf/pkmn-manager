<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\MoveMachine;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonService;
use App\Service\TextService;
use App\Service\Versions\GenerationService;
use App\Service\Versions\VersionGroupService;
use App\Repository\Moves\PokemonMovesLearnVersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonMovesLearnVersionService extends AbstractService
{

    /**
     * @var MoveService $moveManager
     */
    private MoveService $moveManager;

    /**
     * @var PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    private PokemonMovesLearnVersionRepository $repoPokemonMoves;

    /**
     * @var VersionGroupService $versionGroupManager
     */
    private VersionGroupService $versionGroupManager;

    /**
     * @var GenerationService $generationManager
     */
    private GenerationService $generationManager;

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiManager
     * @param TextService $textManager
     * @param MoveService $moveManager
     * @param GenerationService $generationManager
     * @param VersionGroupService $versionGroupManager
     * @param PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiManager,
        TextService $textManager,
        MoveService $moveManager,
        GenerationService $generationManager,
        VersionGroupService $versionGroupManager,
        PokemonMovesLearnVersionRepository $repoPokemonMoves
    )
    {
        $this->moveManager = $moveManager;
        $this->repoPokemonMoves = $repoPokemonMoves;
        $this->generationManager = $generationManager;
        $this->versionGroupManager = $versionGroupManager;
        parent::__construct($em, $apiManager, $textManager);
    }

    /**
     * @param string $slug
     * @return PokemonMovesLearnVersion|null
     * @throws NonUniqueResultException
     */
    public function getPokemonMovesLearnVersionBySlug(string $slug): ?PokemonMovesLearnVersion
    {
        return $this->repoPokemonMoves->getPokemonMovesLearnVersionBySlug($slug);
    }

    /**
     * @param Move|null $move
     * @return int|mixed|string
     */
    public function getMoveLearnByPokemon(?Move $move)
    {
        return $this->repoPokemonMoves->getMoveLearnByPokemon($move);
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
            $move = $this->moveManager->getMoveBySlug(
                $language->getCode() . '/move-'.$moveName
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
                        $versionGroupName
                    ;

                    if (($pokemonMoveLearn = $this->getPokemonMovesLearnVersionBySlug($slug)) === null)
                    {
                        $pokemonMoveLearn = (new PokemonMovesLearnVersion())
                            ->setMove($move)
                            ->setPokemon($pokemon)
                            ->setVersionGroup($versionGroup)
                            ->setMoveLearnMethod($moveLearnMethod)
                            ->setLevel($detailGroup['level_learned_at'])
                            ->setSlug($slug)
                        ;
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