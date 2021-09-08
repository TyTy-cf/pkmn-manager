<?php


namespace App\Form\PokemonSheet;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PokemonSheetStatsFormType.php
 *
 * @author Kevin Tourret
 */
class StatsFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hp', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.hp',
                ]
            ])
            ->add('atk', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.atk',
                ]
            ])
            ->add('def', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.def',
                ]
            ])
            ->add('spa', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.spa',
                ]
            ])
            ->add('spd', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.spd',
                ]
            ])
            ->add('spe', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.stats.spe',
                ]
            ]
        );
    }
}
