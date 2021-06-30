<?php


namespace App\Service\Pokemon;


use App\Entity\Infos\Nature;
use App\Entity\Pokemon\Pokemon;

/**
 * Class StatsCalculatorService.php
 *
 * @author Kevin Tourret
 */
class StatsCalculatorService
{

    // IV

    public function getIVFromStatsAndEv(
        Pokemon $pokemon, Nature $nature, int $level, int $hp, int $atk,int $def,
        int $spa, int $spd, int $spe, int $evHp, int $evAtk, int $evDef, int $evSpa, int $evSpd, int $evSpe
    ): array
    {
        $stats['hp'] = $this->calculateIVHPFromStat($hp, $level, $evHp, $pokemon->getHp());
        $stats['atk'] = $this->calculateIVFromStat($atk, $nature->getAtk(), $level, $evAtk, $pokemon->getAtk());
        $stats['def'] = $this->calculateIVFromStat($def, $nature->getDef(), $level, $evDef, $pokemon->getDef());
        $stats['spa'] = $this->calculateIVFromStat($spa, $nature->getSpa(), $level, $evSpa, $pokemon->getSpa());
        $stats['spd'] = $this->calculateIVFromStat($spd, $nature->getSpd(), $level, $evSpd, $pokemon->getSpd());
        $stats['spe'] = $this->calculateIVFromStat($spe, $nature->getSpe(), $level, $evSpe, $pokemon->getSpe());

        return $stats;
    }

    private function calculateIVHPFromStat(int $stat, int $level, int $ev, int $baseStat) {
        $value = (($stat - 10) * 100) / $level - (2 * $baseStat) - ($ev / 4) - 100;
        if ($value < 0) {
            return  '<span class="error-calculator">Err. IV < 0</span>';
        } else if ($value > 31) {
            return '<span class="error-calculator">Err. IV > 31</span>';
        }
        return ceil($value);
    }

    private function calculateIVFromStat(int $stat, float $coefNature, int $level, int $ev, int $baseStat) {
        $value = (($stat / $coefNature - 5) * 100) / $level - $ev / 4 - 2 * $baseStat;
        if ($value < 0) {
            return '<span class="error-calculator">Err. IV < 0</span>';
        } else if ($value > 31) {
            return '<span class="error-calculator">Err. IV > 31</span>';
        }
        return ceil($value);
    }

    // Stats

    public function getStatsFromIvAndEv(
        Pokemon $pokemon, Nature $nature, int $level, int $ivHp, int $ivAtk,int $ivDef,
        int $ivSpa, int $ivSpd, int $ivSpe, int $evHp, int $evAtk, int $evDef, int $evSpa, int $evSpd, int $evSpe
    ): array
    {
        $stats['hp'] = $this->calculateStatsHPFromIvEv($ivHp, $level, $evHp, $pokemon->getHp());
        $stats['atk'] = $this->calculateStatsFromIvEv($ivAtk, $nature->getAtk(), $level, $evAtk, $pokemon->getAtk());
        $stats['def'] = $this->calculateStatsFromIvEv($ivDef, $nature->getDef(), $level, $evDef, $pokemon->getDef());
        $stats['spa'] = $this->calculateStatsFromIvEv($ivSpa, $nature->getSpa(), $level, $evSpa, $pokemon->getSpa());
        $stats['spd'] = $this->calculateStatsFromIvEv($ivSpd, $nature->getSpd(), $level, $evSpd, $pokemon->getSpd());
        $stats['spe'] = $this->calculateStatsFromIvEv($ivSpe, $nature->getSpe(), $level, $evSpe, $pokemon->getSpe());

        return $stats;
    }

    private function calculateStatsFromIvEv(int $iv, float $coefNature, int $level, int $ev, int $baseStat) {
        if ($iv < 0) {
            return '<span class="error-calculator">Err. IV < 0</span>';
        } else if ($iv > 31) {
            return '<span class="error-calculator">Err. IV > 31</span>';
        }
        return floor((((2 * $baseStat + $iv + floor($ev/4)) * $level) / 100 + 5) * $coefNature);
    }

    private function calculateStatsHPFromIvEv(int $iv, int $level, int $ev, int $baseStat) {
        if ($iv < 0) {
            return '<span class="error-calculator">Err. IV < 0</span>';
        } else if ($iv > 31) {
            return '<span class="error-calculator">Err. IV > 31</span>';
        }
        return floor(((2 * $baseStat + $iv + floor($ev/4) + 100) * $level) / 100 + 10);
    }

    // Checker IV

    public function getIvRange(Pokemon $pokemon, Nature $nature, int $level, array $stats, array $evs): array {
        $ivs = $this->getIVFromStatsAndEv(
            $pokemon, $nature, $level,
            $stats['hp'], $stats['atk'], $stats['def'], $stats['spa'], $stats['spd'], $stats['spe'],
            $evs['hp'], $evs['atk'], $evs['def'], $evs['spa'], $evs['spd'], $evs['spe']
        );
        if (is_string($ivs['hp'])) {
            $range['hp'] = $ivs['hp'];
        } else {
            $range['hp'] = [$ivs['hp'], $this->getRange($ivs['hp'], $pokemon->getHp(), 0.0, $level, $evs['hp'], $stats['hp'], true)];
        }
        if (is_string($ivs['atk'])) {
            $range['atk'] = $ivs['atk'];
        } else {
            $range['atk'] = [$ivs['atk'], $this->getRange($ivs['atk'], $pokemon->getAtk(), $nature->getAtk(), $level, $evs['atk'], $stats['atk'])];
        }
        if (is_string($ivs['def'])) {
            $range['def'] = $ivs['def'];
        } else {
            $range['def'] = [$ivs['def'], $this->getRange($ivs['def'], $pokemon->getDef(), $nature->getDef(), $level, $evs['def'], $stats['def'])];
        }
        if (is_string($ivs['spa'])) {
            $range['spa'] = $ivs['spa'];
        } else {
            $range['spa'] = [$ivs['spa'], $this->getRange($ivs['spa'], $pokemon->getSpa(), $nature->getSpa(), $level, $evs['spa'], $stats['spa'])];
        }
        if (is_string($ivs['spd'])) {
            $range['spd'] = $ivs['spd'];
        } else {
            $range['spd'] = [$ivs['spd'], $this->getRange($ivs['spd'], $pokemon->getSpd(), $nature->getSpd(), $level, $evs['spd'], $stats['spd'])];
        }
        if (is_string($ivs['spe'])) {
            $range['spe'] = $ivs['spe'];
        } else {
            $range['spe'] = [$ivs['spe'], $this->getRange($ivs['spe'], $pokemon->getSpe(), $nature->getSpe(), $level, $evs['spe'], $stats['spe'])];
        }

        return $range;
    }

    private function getRange($iv, int $baseStat, float $coefNature, int $level, int $ev, int $stats, bool $isHp = false): int
    {
        if ($iv == 31) {
            return $iv;
        }
        $newStats = floor($this->calculateStatsFromIvEv($iv, $coefNature, $level, $ev, $baseStat));
        if ($isHp) {
            $newStats = floor($this->calculateStatsHPFromIvEv($iv, $level, $ev, $baseStat));
        }
        while ($newStats <= $stats) {
            if ($iv == 31) {
                break;
            }
            $newStats = floor($this->calculateStatsFromIvEv($iv, $coefNature, $level, $ev, $baseStat));
            if ($isHp) {
                $newStats = floor($this->calculateStatsHPFromIvEv($iv, $level, $ev, $baseStat));
            }
            $iv++;
        }
        return $iv;
    }

    public function getStatsRange(?Pokemon $pokemon, ?Nature $nature, int $level, array $arrayIv, array $arrayEv): array
    {
        return $this->getStatsFromIvAndEv(
            $pokemon, $nature, $level,
            $arrayIv['hp'], $arrayIv['atk'], $arrayIv['def'], $arrayIv['spa'], $arrayIv['spd'], $arrayIv['spe'],
            $arrayEv['hp'], $arrayEv['atk'], $arrayEv['def'], $arrayEv['spa'], $arrayEv['spd'], $arrayEv['spe']
        );
    }

}