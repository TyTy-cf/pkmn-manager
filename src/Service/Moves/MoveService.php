<?php

namespace App\Service\Moves;

use App\Entity\Moves\DamageClass;
use App\Entity\Moves\Move;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Users\Language;
use App\Repository\Infos\Type\TypeRepository;
use App\Repository\Moves\DamageClassRepository;
use App\Repository\Pokedex\EvolutionChainLinkRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Service\AbstractService;
use App\Service\Api\ApiService;
use App\Service\TextService;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class MoveService
 * @package App\Service\Moves
 *
 * @property MoveRepository $movesRepository
 * @property MoveDescriptionService $moveDescriptionManager
 * @property TypeRepository $typeRepository
 * @property DamageClassRepository damageClassRepository
 * @property PokemonRepository $pokemonRepository
 */
class MoveService extends AbstractService
{

    /**
     * MoveService constructor
     * @param EntityManagerInterface $em
     * @param ApiService $apiService
     * @param TextService $textService
     * @param DamageClassRepository $damageClassRepository
     * @param MoveDescriptionService $moveDescriptionService
     * @param MoveRepository $moveRepository
     * @param TypeRepository $typeRepository
     * @param PokemonRepository $pokemonRepository
     */
    public function __construct (
        EntityManagerInterface $em,
        ApiService $apiService,
        TextService $textService,
        DamageClassRepository $damageClassRepository,
        MoveDescriptionService $moveDescriptionService,
        MoveRepository $moveRepository,
        TypeRepository $typeRepository,
        PokemonRepository $pokemonRepository
    ) {
        $this->movesRepository = $moveRepository;
        $this->typeRepository = $typeRepository;
        $this->damageClassRepository = $damageClassRepository;
        $this->moveDescriptionManager = $moveDescriptionService;
        $this->pokemonRepository = $pokemonRepository;
        parent::__construct($em, $apiService, $textService);
    }

    /**
     * @param string $slug
     * @return Move|null
     * @throws NonUniqueResultException
     */
    public function getSimpleMoveBySlug(string $slug): ?Move {
        return $this->movesRepository->getSimpleMoveBySlug($slug);
    }

    /**
     * @param Pokemon $pokemon
     * @return array
     */
    public function findMovesByPokemon(Pokemon $pokemon): array {
        $evolutionChain = $pokemon->getPokemonSpecies()->getEvolutionChain();
        if ($evolutionChain === null) {
            return $this->movesRepository->getMovesByPokemons([$pokemon]);
        }
        return $this->movesRepository->getMovesByPokemons(
            $this->pokemonRepository->findByPokemonSpeciesEvolutionChain($evolutionChain)
        );
    }

    /**
     * If not exist, save the moves in database
     * @param Language $language
     * @param $apiResponse
     * @throws TransportExceptionInterface
     */
    public function createFromApiResponse(Language $language, $apiResponse)
    {
        $slug = $this->textService->generateSlugFromClassWithLanguage(
            $language, Move::class, $apiResponse['name']
        );
        $urlDetailedMove = $this->apiService->apiConnect($apiResponse['url'])->toArray();

        if (($urlDetailedMove['pp'] !== null
         || $urlDetailedMove['power'] !== null
         || $urlDetailedMove['accuracy'] !== null)
         && isset($urlDetailedMove['damage_class'])
        ) {
            $isNew = false;
            if (null === $move = $this->movesRepository->findOneBySlug($slug)) {
                $move = new Move();
                $isNew = true;
            }

            $codeLanguage = $language->getCode();
            $moveName = $this->apiService->getNameBasedOnLanguageFromArray($language->getCode(), $urlDetailedMove);

            // Get the DamageClass
            $damageClass = $this->damageClassRepository->findOneBySlug(
                $this->textService->generateSlugFromClassWithLanguage(
                    $language,
                    DamageClass::class,
                    $urlDetailedMove['damage_class']['name']
                )
            );

            // Get the Type
            $typeNameLang = $this->apiService->getNameBasedOnLanguage($codeLanguage, $urlDetailedMove['type']['url']);
            $slugType = $codeLanguage. '-' . $this->textService->slugify($typeNameLang);
            $type = $this->typeRepository->findOneBySlug($slugType);

            if ($isNew) {
                $move->setLanguage($language);
                $this->entityManager->persist($move);
            }

            $slug = $codeLanguage. '-' . $this->textService->slugify($moveName);
            $move->setType($type)
                ->setSlug($slug)
                ->setName($moveName)
                ->setPp($urlDetailedMove['pp'])
                ->setDamageClass($damageClass)
                ->setPower($urlDetailedMove['power'])
                ->setPriority($urlDetailedMove['priority'])
                ->setAccuracy($urlDetailedMove['accuracy'])
            ;

            // Create the MoveDescription
            $this->moveDescriptionManager->createMoveDescription(
                $language,
                $move,
                $urlDetailedMove['flavor_text_entries']
            );
        }
    }

}
