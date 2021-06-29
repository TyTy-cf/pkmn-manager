<?php


namespace App\Controller\Pokemon;

use App\Form\CalculateIvFormType;
use App\Repository\Infos\NatureRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Pokemon\PokemonSpeciesVersionRepository;
use App\Repository\Pokemon\PokemonSpritesVersionRepository;
use App\Service\Infos\NatureService;
use App\Service\Infos\Type\TypeService;
use App\Service\Pokedex\EvolutionChainService;
use App\Service\Pokemon\PokemonService;
use App\Service\Pokemon\StatsCalculatorService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class PokemonController extends AbstractController
{

    /**
     * @var PokemonService
     */
    private PokemonService $pokemonService;

    /**
     * @var LanguageService $languageService
     */
    private LanguageService $languageService;

    /**
     * @var EvolutionChainService $evolutionChainService
     */
    private EvolutionChainService $evolutionChainService;

    /**
     * @var PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository
     */
    private PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository;

    /**
     * @var PokemonSpritesVersionRepository $pokemonSpritesVersionRepository
     */
    private PokemonSpritesVersionRepository $pokemonSpritesVersionRepository;

    /**
     * @var PokemonRepository $pokemonRepository
     */
    private PokemonRepository $pokemonRepository;

    /**
     * @var TypeService $typeService
     */
    private TypeService $typeService;

    /**
     * @var NatureRepository $natureRepository
     */
    private NatureRepository $natureRepository;

    /**
     * @var StatsCalculatorService $statsCalculatorService
     */
    private StatsCalculatorService $statsCalculatorService;

    /**s
     * PokemonController constructor.
     *
     * @param PokemonService $pokemonService
     * @param EvolutionChainService $evolutionChainService
     * @param LanguageService $languageService
     * @param PokemonRepository $pokemonRepository
     * @param PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository
     * @param PokemonSpritesVersionRepository $pokemonSpritesVersionRepository
     * @param TypeService $typeService
     * @param NatureRepository $natureRepository
     * @param StatsCalculatorService $statsCalculatorService
     */
    public function __construct(
        PokemonService $pokemonService,
        EvolutionChainService $evolutionChainService,
        LanguageService $languageService,
        PokemonRepository $pokemonRepository,
        PokemonSpeciesVersionRepository $pokemonSpeciesVersionRepository,
        PokemonSpritesVersionRepository $pokemonSpritesVersionRepository,
        TypeService $typeService,
        NatureRepository $natureRepository,
        StatsCalculatorService $statsCalculatorService
    ) {
        $this->evolutionChainService = $evolutionChainService;
        $this->pokemonRepository = $pokemonRepository;
        $this->pokemonService = $pokemonService;
        $this->typeService = $typeService;
        $this->languageService = $languageService;
        $this->natureRepository = $natureRepository;
        $this->statsCalculatorService = $statsCalculatorService;
        $this->pokemonSpeciesVersionRepository = $pokemonSpeciesVersionRepository;
        $this->pokemonSpritesVersionRepository = $pokemonSpritesVersionRepository;
    }

    /**
     * Display the characteristic for one pokemon
     *
     * @Route(path="/pokemon/{slug_pokemon}", name="profile_pokemon", requirements={"slug_pokemon": ".+"})
     *
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    function displayProfile(Request $request): Response
    {
        $pokemon = $this->pokemonRepository->getPokemonProfileBySlug($request->get('slug_pokemon'));

        $formCalculateIv = $this->createForm(CalculateIvFormType::class);

        return $this->render('Pokemon/profile.html.twig', [
            'pokemon' => $pokemon,
            'arrayMoves' => $this->pokemonService->getArrayMovesByVersionForPokemon($pokemon),
            'arrayEvolutionChain' => $this->evolutionChainService->getEvolutionChainFromPokemon($pokemon),
            'arrayDescriptionVersion' => $this->pokemonSpeciesVersionRepository->getDescriptionVersionByVersionsAndPokemon(
                $pokemon->getPokemonSpecies()
            ),
            'spritesVersionGroup' => $this->pokemonSpritesVersionRepository->getSpritesVersionGroupByPokemon($pokemon),
            'typesRelation' => $this->typeService->getTypesWeaknessesByPokemon($pokemon),
            'types' => $this->typeService->getAllTypeByLanguage($pokemon->getLanguage()),
            'formCalculateIv' => $formCalculateIv->createView(),
        ]);
    }

    /**
     * @Route(path="/pokemons/calculate_iv/{datas}", name="calculate_iv")
     *
     * @param Request $request
     * @return string
     */
    function calculateIv(Request $request): Response
    {
        $jsonIv = json_decode($request->get('datas'), true);
        $pokemon = $this->pokemonRepository->findOneBy(['id' => $jsonIv['idPokemon']]);
        $nature = $this->natureRepository->findOneBy(['id' => $jsonIv['nature']]);
        $level = intval($jsonIv['level']) === 0 ? 1 : intval($jsonIv['level']);

        $range = $this->statsCalculatorService->getIvRange(
            $pokemon, $nature, $level,
            [
                'hp' => intval($jsonIv['statsHp']),
                'atk' => intval($jsonIv['statsAtk']),
                'def' => intval($jsonIv['statsDef']),
                'spa' => intval($jsonIv['statsSpa']),
                'spd' => intval($jsonIv['statsSpd']),
                'spe' => intval($jsonIv['statsSpe']),
            ],
            [
                'hp' => intval($jsonIv['evHp']),
                'atk' => intval($jsonIv['evAtk']),
                'def' => intval($jsonIv['evDef']),
                'spa' => intval($jsonIv['evSpa']),
                'spd' => intval($jsonIv['evSpd']),
                'spe' => intval($jsonIv['evSpe']),
            ]
        );

        return $this->render('Pokemon/Resume/_result_stats_calculator.html.twig', [
            'range' => $range,
        ]);

//        dump($range);
//        die();
    }

    /**
     * @Route(path="/pokemons/searchPokemonByName/{approxName}", name="get_pokemon_by_name")
     *
     * @param string $approxName
     * @return JsonResponse
     */
    function searchPokemonByName(string $approxName): JsonResponse {
        return new JsonResponse($this->pokemonRepository->getPokemonNameForLanguage(
            $this->languageService->getLanguageByCode('fr'),
            $approxName
        ));
    }
}
