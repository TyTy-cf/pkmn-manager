<?php


namespace App\Controller\Pokemon;


use App\Entity\Infos\Nature;
use App\Entity\Pokemon\Pokemon;
use App\Repository\Infos\NatureRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Service\Pokemon\StatsCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokemonCalculatorController.php
 *
 * @author Kevin Tourret
 *
 * @property NatureRepository $natureRepository
 * @property PokemonRepository $pokemonRepository
 * @property StatsCalculatorService $statsCalculatorService
 */
class PokemonCalculatorController extends AbstractController
{

    /**
     * PokemonCalculatorController constructor.
     * @param NatureRepository $natureRepository
     * @param PokemonRepository $pokemonRepository
     * @param StatsCalculatorService $statsCalculatorService
     */
    public function __construct(
        NatureRepository $natureRepository,
        PokemonRepository $pokemonRepository,
        StatsCalculatorService $statsCalculatorService
    ) {
        $this->natureRepository = $natureRepository;
        $this->pokemonRepository = $pokemonRepository;
        $this->statsCalculatorService = $statsCalculatorService;
    }

    /**
     * @Route(path="/pokemons/calculate/{datas}", name="calculate")
     *
     * @param Request $request
     * @return JsonResponse
     */
    function calculate(Request $request): JsonResponse {
        $json = json_decode($request->get('datas'), true);
        $pokemon = $this->pokemonRepository->findOneBy(['id' => $json['idPokemon']]);
        $nature = $this->natureRepository->findOneBy(['id' => $json['nature']]);
        $level = intval($json['level']) === 0 ? 1 : intval($json['level']);
        $range = [];
        if ($json['toCalculate'] === 'iv') {
            $range = $this->calculateIv($pokemon, $nature, $level, $json);
        } elseif ($json['toCalculate'] === 'stats') {
            $range = $this->calculateStats($pokemon, $nature, $level, $json);
        }

        return (new JsonResponse())->setData([
            'html' => $this->renderView('Pokemon/Resume/_result_stats_calculator.html.twig', [
                'range' => $range,
            ]),
        ]);
    }

    /**
     * @param Pokemon $pokemon
     * @param Nature $nature
     * @param int $level
     * @param $json
     * @return array
     */
    function calculateIv(Pokemon $pokemon, Nature $nature, int $level, $json): array
    {
       return $this->statsCalculatorService->getIvRange(
            $pokemon, $nature, $level,
            [
                'hp' => intval($json['statsHp']),
                'atk' => intval($json['statsAtk']),
                'def' => intval($json['statsDef']),
                'spa' => intval($json['statsSpa']),
                'spd' => intval($json['statsSpd']),
                'spe' => intval($json['statsSpe']),
            ],
            [
                'hp' => intval($json['evHp']),
                'atk' => intval($json['evAtk']),
                'def' => intval($json['evDef']),
                'spa' => intval($json['evSpa']),
                'spd' => intval($json['evSpd']),
                'spe' => intval($json['evSpe']),
            ]
        );
    }

    /**
     *
     * @param Pokemon $pokemon
     * @param Nature $nature
     * @param int $level
     * @param $json
     * @return array
     */
    function calculateStats(Pokemon $pokemon, Nature $nature, int $level, $json): array
    {
        return $this->statsCalculatorService->getStatsRange(
            $pokemon, $nature, $level,
            [
                'hp' => intval($json['ivHp']),
                'atk' => intval($json['ivAtk']),
                'def' => intval($json['ivDef']),
                'spa' => intval($json['ivSpa']),
                'spd' => intval($json['ivSpd']),
                'spe' => intval($json['ivSpe']),
            ],
            [
                'hp' => intval($json['evHp']),
                'atk' => intval($json['evAtk']),
                'def' => intval($json['evDef']),
                'spa' => intval($json['evSpa']),
                'spd' => intval($json['evSpd']),
                'spe' => intval($json['evSpe']),
            ]
        );
    }


}
