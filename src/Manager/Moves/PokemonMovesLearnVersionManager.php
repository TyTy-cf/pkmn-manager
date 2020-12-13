<?php


namespace App\Manager\Moves;


use App\Entity\Moves\MoveLearnMethod;
use App\Entity\Moves\PokemonMovesLearnVersion;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Entity\Versions\Generation;
use App\Entity\Versions\Version;
use App\Entity\Versions\VersionGroup;
use App\Manager\AbstractManager;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\TextManager;
use App\Manager\Versions\GenerationManager;
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
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * @var GenerationManager $generationManager
     */
    private GenerationManager $generationManager;

    /**
     * MoveManager constructor
     * @param EntityManagerInterface $em
     * @param ApiManager $apiManager
     * @param TextManager $textManager
     * @param MoveManager $moveManager
     * @param GenerationManager $generationManager
     * @param VersionGroupManager $versionGroupManager
     * @param MoveLearnMethodManager $moveLearnMethodManager
     * @param PokemonMovesLearnVersionRepository $repoPokemonMoves
     */
    public function __construct
    (
        EntityManagerInterface $em,
        ApiManager $apiManager,
        TextManager $textManager,
        MoveManager $moveManager,
        GenerationManager $generationManager,
        VersionGroupManager $versionGroupManager,
        MoveLearnMethodManager $moveLearnMethodManager,
        PokemonMovesLearnVersionRepository $repoPokemonMoves
    )
    {
        $this->moveManager = $moveManager;
        $this->repoPokemonMoves = $repoPokemonMoves;
        $this->generationManager = $generationManager;
        $this->versionGroupManager = $versionGroupManager;
        $this->moveLearnMethodManager = $moveLearnMethodManager;
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
     * @param Pokemon $pokemon
     * @param Language $language
     * @return array
     */
    public function generateArrayMovesForPokemon(Pokemon $pokemon, Language $language) {
        // Initialise the array of VersionGroup
        $arrayMoves = [];
        $arrayMoves['version_groups'] = array();
        $allMoveLearnMethod = $this->moveLearnMethodManager->getAllMoveLearnMethodByLanguage($language);
            $versionsGroups = $this->versionGroupManager->getVersionGroupByLanguage($language);
            if (sizeof($versionsGroups) > 0) {
                foreach($versionsGroups as $versionGroup) {
                    if (!in_array($versionGroup->getName(), VersionGroup::$avoidList)) {
                        $arrayMovesLearn = [];
                        foreach($allMoveLearnMethod as $moveLearnMethod) {
                            /** @var MoveLearnMethod $moveLearnMethod */
                            $moves = $this->repoPokemonMoves->getMovesLearnBy($pokemon, $moveLearnMethod, $versionGroup);
                            if (!empty($moves)) {
                                $arrayMovesLearn[$moveLearnMethod->getName()] = $moves;
                            }
                        }
                        if (count($arrayMovesLearn) > 0) {
                            array_push($arrayMoves['version_groups'], [
                                'id' => $versionGroup->getSlug(),
                                'name' => $versionGroup->getName(),
                            ]);
                            $arrayMoves['moves_infos'][$versionGroup->getSlug()] = [
                                'moves' => $arrayMovesLearn
                            ];
                        }
                    }
                }
        }
        return $arrayMoves;
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
                        $versionGroupName;

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