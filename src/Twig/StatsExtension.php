<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StatsExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('percent', [$this, 'getPercentByStats']),
            new TwigFilter('statsCss', [$this, 'getCssForStats']),
        ];
    }

    /**
     * @param int $stats
     * @return float
     */
    public function getPercentByStats(int $stats): float {
        $percent = ($stats/200) * 100;
        return $percent > 100 ? 100 : $percent;
    }

    /**
     * @param int $stats
     * @return string
     */
    public function getCssForStats(int $stats): string {
        if ($stats <= 40) {
            return 'stats-very-low';
        } else if ($stats > 40 && $stats <= 60) {
            return 'stats-low';
        } else if ($stats > 60 && $stats <= 80) {
            return 'stats-average-low';
        } else if ($stats > 80 && $stats <= 100) {
            return 'stats-average';
        } else if ($stats > 100 && $stats <= 130) {
            return 'stats-high-low';
        } else if ($stats > 130 && $stats <= 160) {
            return 'stats-high';
        } else {
            return 'stats-very-high';
        }
    }

}
