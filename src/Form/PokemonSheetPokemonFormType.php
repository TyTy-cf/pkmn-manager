<?php


namespace App\Form;


use App\Entity\Infos\Ability;
use App\Entity\Infos\PokemonAbility;
use App\Entity\Moves\Move;
use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\PokemonAbilityRepository;
use App\Repository\Moves\MoveRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PokemonSheetPokemonFormType.php
 *
 * @author Kevin Tourret
 *
 * @property MoveRepository $moveRepository
 * @property AbilityRepository $abilityRepository
 */
class PokemonSheetPokemonFormType extends AbstractType
{

    /**
     * PokemonSheetMoveFormType constructor.
     * @param MoveRepository $moveRepository
     * @param AbilityRepository $abilityRepository
     */
    public function __construct(
        MoveRepository $moveRepository,
        AbilityRepository $abilityRepository
    )
    {
        $this->moveRepository = $moveRepository;
        $this->abilityRepository = $abilityRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pokemon = $options['data'];
        $moves = $this->moveRepository->getMovesByPokemon($pokemon->getPokemon());
        $builder
            ->add('ability', EntityType::class, [
                'label' => 'pokemon_sheet.form.field.ability',
                'class' => Ability::class,
                'choices' => $this->abilityRepository->getAbilitysByPokemon($pokemon->getPokemon()),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('moves', EntityType::class, [
                'label' => 'pokemon_sheet.form.field.moves',
                'class' => Move::class,
                'by_reference' => false,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'allow_add' => true,
                'add_btn_class' => 'btn-arrow blue-btn-arrow mt-2',
                'add_btn_label_translation_domain' => 'back_office',
                'add_btn_label' => 'stage.availabilities.btn.add',
                'allow_delete' => true,
                'delete_btn_class' => 'btn-arrow red-btn-arrow',
                'delete_btn_label_translation_domain' => 'back_office',
                'delete_btn_label' => 'stage.availabilities.btn.delete',
                'attr' => [
                    'class' => 'mt-2 mb-2',
                ],
                'entry_options' => [
                    'collapsable' => true,
                    'attr' => [
                        'data-form-collapsable' => false,
                        'class' => 'mt-2',
                    ],
                    'label' => false,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'pokemon_sheet.form.submit',
                'attr' => [
                    'class' => 'btn btn-primary ml-1 btn-search-pokemon',
                ],
            ]);
        ;
    }



}
