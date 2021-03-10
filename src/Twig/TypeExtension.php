<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TypeExtension.php
 *
 * @author Kevin Tourret
 */
class TypeExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('type_return', [$this, 'getTypeReturn']),
        ];
    }

    /**
     * @param float $coef
     * @return float|string|string[]
     */
    public function getTypeReturn(float $coef) {
        return str_replace('.', '', $coef);
    }

}
