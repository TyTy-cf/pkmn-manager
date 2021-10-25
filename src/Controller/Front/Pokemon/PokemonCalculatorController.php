<?php


namespace App\Controller\Front\Pokemon;


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
        $calculation = $json['toCalculate'];
        if ($calculation === 'iv') {
            $range = $this->calculateIv($pokemon, $nature, $level, $json);
        } elseif ($calculation === 'stats') {
            $range = $this->calculateStats($pokemon, $nature, $level, $json);
        }

        return (new JsonResponse())->setData([
            'html' => $this->renderView('Pokemon/Resume/_result_stats_calculator.html.twig', [
                'range' => $range,
            ]),
            'calculation' => $calculation,
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
            $this->getArrayStatsIvFromJsonArray($json),
            $this->getArrayEvFromJsonArray($json)
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
            $this->getArrayStatsIvFromJsonArray($json),
            $this->getArrayEvFromJsonArray($json)
        );
    }

    /**
     * Generate the array for IV or stats
     * @param $json
     * @return array
     */
    function getArrayStatsIvFromJsonArray($json): array {
        return [
            'hp' => intval($json['hp']),
            'atk' => intval($json['atk']),
            'def' => intval($json['def']),
            'spa' => intval($json['spa']),
            'spd' => intval($json['spd']),
            'spe' => intval($json['spe']),
        ];
    }

    /**
     * Generate the array for EV
     * @param $json
     * @return array
     */
    function getArrayEvFromJsonArray($json): array {
        return [
            'hp' => intval($json['evHp']),
            'atk' => intval($json['evAtk']),
            'def' => intval($json['evDef']),
            'spa' => intval($json['evSpa']),
            'spd' => intval($json['evSpd']),
            'spe' => intval($json['evSpe']),
        ];
    }

}
