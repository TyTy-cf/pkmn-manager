<?php


namespace App\Twig;


use App\Entity\Infos\Ability;
use App\Repository\Infos\AbilityVersionGroupRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AbilityExtension.php
 *
 * @author Kevin Tourret
 *
 * @property AbilityVersionGroupRepository $abilityVersionGroupRepository
 */
class AbilityExtension extends AbstractExtension
{

    /**
     * AbilityExtension constructor.
     *
     * @param AbilityVersionGroupRepository $abilityVersionGroupRepository
     */
    public function __construct(AbilityVersionGroupRepository $abilityVersionGroupRepository)
    {
        $this->abilityVersionGroupRepository = $abilityVersionGroupRepository;
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('last_description', [$this, 'getLastDescription']),
        ];
    }

    /**
     * @param Ability $ability
     * @return float|string|string[]
     */
    public function getLastDescription(Ability $ability) {
        return $this->abilityVersionGroupRepository->findLastDescriptionByAbility($ability)->getDescription();
    }

}
