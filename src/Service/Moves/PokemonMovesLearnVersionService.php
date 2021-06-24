<?php


namespace App\Service\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\Moves\MoveRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
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
     * @var PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    private PokemonMovesLearnVersionRepository $repoPokemonMoves;

    /**
     * @var VersionGroupService $versionGroupService
     */
    private VersionGroupService $versionGroupService;

    /**
     * @var GenerationService $generationService
     */
    private GenerationService $generationService;

    /**
     * @var MoveRepository $movesRepository
     */
    private MoveRepository $movesRepository;

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiService
     * @param TextService $textService
     * @param MoveRepository $movesRepository
     * @param GenerationService $generationService
     * @param VersionGroupService $versionGroupService
     * @param PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiService $apiService,
        TextService $textService,
        MoveRepository $movesRepository,
        GenerationService $generationService,
        VersionGroupService $versionGroupService,
        PokemonMovesLearnVersionRepository $repoPokemonMoves
    )
    {
        $this->movesRepository = $movesRepository;
        $this->repoPokemonMoves = $repoPokemonMoves;
        $this->generationService = $generationService;
        $this->versionGroupService = $versionGroupService;
        parent::__construct($em, $apiService, $textService);
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
        return $this->repoPokemonMoves->getMoveLearnByMove($move);
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
            $this->apiService->getPokemonFromName($pokemon->getNameApi())->getContent(),
            true
        );

        // Fetchs all moves
        foreach ($urlDetailed['moves'] as $detailedMove)
        {
            // Fetch the move from data
            $moveName = $detailedMove['move']['name'];
            $codeLang = $language->getCode();
            $move = $this->movesRepository->findOneBySlug(
                $codeLang . '/move-'.$moveName
            );
            foreach ($detailedMove['version_group_details'] as $detailGroup)
            {
                $versionGroupName = $detailGroup['version_group']['name'];
                if (isset($arrayGroupVersion[$versionGroupName])) {
                    $versionGroup = $arrayGroupVersion[$versionGroupName];
                    $moveLearnMethodName = $detailGroup['move_learn_method']['name'];
                    $slugMoveLeanMethod = $codeLang . '/move-learn-method-'.$moveLearnMethodName;
                    if (isset($arrayMoveLearnMethod[$slugMoveLeanMethod]))
                    {
                        $moveLearnMethod = $arrayMoveLearnMethod[$slugMoveLeanMethod];
                        // Slug
                        $slug = $codeLang.'-'.
                            $pokemon->getNameApi().'-'.
                            $moveName.'-'.
                            $moveLearnMethodName.'-'.
                            $versionGroupName
                        ;

                        if (null === $pokemonMoveLearn = $this->getPokemonMovesLearnVersionBySlug($slug))
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
                    }
                }
            }
            $this->entityManager->flush();
        }
    }

}
