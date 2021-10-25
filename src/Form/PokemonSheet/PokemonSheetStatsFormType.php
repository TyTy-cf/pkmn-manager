<?php


namespace App\Form\PokemonSheet;


use App\Entity\Pokemon\PokemonSheet;
use App\Entity\Stats\Stats;
use App\Entity\Stats\StatsEv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PokemonSheetStatsFormType.php
 *
 * @author Kevin Tourret
 */
class PokemonSheetStatsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level', NumberType::class, [
                'label' => 'form_stats.level',
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.level',
                ]
            ])
            ->add('stats', StatsFormType::class, [
                'label' => false,
                'required' => true,
                'data_class' => Stats::class,
                'attr' => [
                    'placeholder' => 'form_stats.stats.hp',
                ]
            ])
            ->add('evs', StatsFormType::class, [
                'label' => false,
                'required' => true,
                'data_class' => StatsEv::class,
                'attr' => [
                    'placeholder' => 'form_stats.stats.atk',
                ]
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PokemonSheet::class,
        ]);
    }

}
