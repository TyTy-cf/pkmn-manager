<?php


namespace App\Twig;


use App\Entity\Infos\Nature;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class NatureExtensions.php
 *
 * @author Kevin Tourret
 */
class NatureExtensions extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('statsNatureCss', [$this, 'getCssByStats']),
            new TwigFilter('statsNatureStatsDisplay', [$this, 'getStatsNatureStatsDisplay']),
            new TwigFilter('statsNatureDisplay', [$this, 'getStatsNatureDisplay']),
        ];
    }

    /**
     * @param float $stats
     * @return string
     */
    public function getCssByStats(float $stats): string {
        $cssClass = 'neutral-stats';
        if ($stats === 0.9) {
            $cssClass = 'decreased-stats';
        } else if ($stats === 1.1) {
            $cssClass = 'increased-stats';
        }
        return $cssClass;
    }

    /**
     * @param float $stats
     * @return string
     */
    public function getStatsNatureStatsDisplay(float $stats): string {
        $statsDisplay = '-';
        if ($stats === 0.9) {
            $statsDisplay = '-10%';
        } else if ($stats === 1.1) {
            $statsDisplay = '+10%';
        }
        return $statsDisplay;
    }

}
