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
    public function getPercentByStats(int $stats) {
        $percent = ($stats/200) * 100;
        return $percent > 100 ? 100 : $percent;
    }


}