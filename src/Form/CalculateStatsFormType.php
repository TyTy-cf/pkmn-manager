<?php


namespace App\Form;


use App\Entity\Infos\Nature;
use App\Repository\Infos\NatureRepository;
use App\Repository\Users\LanguageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CalculateStatsFormType.php
 *
 * @author Kevin Tourret
 */
class CalculateStatsFormType extends AbstractType
{

    private NatureRepository $natureRepository;
    private LanguageRepository $languageRepository;

    /**
     * StageType constructor.
     * @param NatureRepository $natureRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(
        NatureRepository $natureRepository,
        LanguageRepository $languageRepository
    )
    {
        $this->natureRepository = $natureRepository;
        $this->languageRepository = $languageRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ivPv', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.hp',
                ]
            ])
            ->add('ivAtk', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.atk',
                ]
            ])
            ->add('ivDef', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.def',
                ]
            ])
            ->add('ivSpa', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.spa',
                ]
            ])
            ->add('ivSpd', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.spd',
                ]
            ])
            ->add('ivSpe', NumberType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'form_stats.iv.spe',
                ]
            ])
            ->add('evPv', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.hp',
                ]
            ])
            ->add('evAtk', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.atk',
                ]
            ])
            ->add('evDef', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.def',
                ]
            ])
            ->add('evSpa', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.spa',
                ]
            ])
            ->add('evSpd', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.spd',
                ]
            ])
            ->add('evSpe', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.ev.spe',
                ]
            ])
            ->add('level', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'form_stats.level',
                ]
            ])
            ->add('nature', EntityType::class, [
                'label' => false,
                'class' => Nature::class,
                'choices' => $this->natureRepository->findBy(
                    ['language' => $this->languageRepository->findOneBy(['code' => 'fr'])],
                    ['name' => 'ASC']
                ),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('submit', ButtonType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ]);
    }

}
