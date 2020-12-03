<?php


namespace App\Manager\Moves;


use App\Entity\Moves\Move;
use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\TextManager;
use App\Manager\Versions\VersionGroupManager;
use App\Repository\Moves\MoveRepository;
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
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * @var PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    private PokemonMovesLearnVersionRepository $repoPokemonMoves;

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

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
     * @param VersionGroupManager $versionGroupManager
     * @param PokemonManager $pokemonManager
     * @param MoveLearnMethodManager $moveLearnMethodManager
     * @param PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TextManager $textManager,
        MoveManager $moveManager,
        PokemonManager $pokemonManager,
        VersionGroupManager $versionGroupManager,
        MoveLearnMethodManager $moveLearnMethodManager,
        PokemonMovesLearnVersionRepository $repoPokemonMoves
    )
    {
        $this->pokemonManager = $pokemonManager;
        $this->moveManager = $moveManager;
        $this->moveLearnMethodManager = $moveLearnMethodManager;
        $this->versionGroupManager = $versionGroupManager;
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
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface|NonUniqueResultException
     */
    public function createMoveFromApiResponse(Language $language, $apiResponse, int $generation)
    {
        $urlDetailed = $this->apiManager->getDetailed($apiResponse['url'])->toArray();
        $pokemonName = $urlDetailed['name'];
        $pokemon = $this->pokemonManager->getPokemonByLanguageAndSlug(
            $language,
            $this->textManager->generateSlugFromClass(Pokemon::class, $pokemonName)
        );
        foreach ($urlDetailed['moves'] as $detailedMove)
        {
            // Fetch the move from data
            $move = $this->moveManager->getMoveByLanguageAndSlug(
                $language,
                $this->textManager->generateSlugFromClass(
                    Move::class,
                    $detailedMove['move']['name']
                )
            );
            foreach ($detailedMove['version_group_details'] as $detailGroup)
            {
                $requiredGeneration = $this->versionGroupManager->getVersionGroupFromGenerationIdAndName(
                    $generation,
                    $detailGroup['version_group']['name']
                );

                if ($requiredGeneration !== null)
                {
                    // Gestion de leech-seed qui est rentré deux fois...

                    if ($detailedMove['move']['name'] !== 'leech-seed'
                     && $detailGroup['level_learned_at'] !== 1)
                    {
                        // Fetch the MoveLearnMethod
                        $moveLearnMethod = $this->moveLearnMethodManager->getMoveLearnMethodByLanguageAndSlug(
                            $language,
                            $this->textManager->generateSlugFromClass(
                                MoveLearnMethod::class,
                                $detailGroup['move_learn_method']['name']
                            )
                        );
                        if ($moveLearnMethod !== null)
                        {
                            // Slug
                            $slug = $language->getCode().'/'.
                                $pokemonName.'-'.
                                $detailedMove['move']['name'].'-'.
                                $detailGroup['move_learn_method']['name'].'-'.
                                $detailGroup['version_group']['name'];

                            if ($this->getPokemonMovesLearnVersionBySlug($slug) === null)
                            {
                                $pokemonMoveLearn = new PokemonMovesLearnVersion();
                                $pokemonMoveLearn->setMove($move);
                                $pokemonMoveLearn->setPokemon($pokemon);
                                $pokemonMoveLearn->setVersionGroup($requiredGeneration);
                                $pokemonMoveLearn->setMoveLearnMethod($moveLearnMethod);
                                $pokemonMoveLearn->setLevel($detailGroup['level_learned_at']);
                                $pokemonMoveLearn->setSlug($slug);
                                $this->entityManager->persist($pokemonMoveLearn);
                            }
                        }

                    }
                }
            }
        }
        $this->entityManager->flush();
    }

}