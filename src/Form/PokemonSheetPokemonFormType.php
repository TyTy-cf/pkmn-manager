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
            ->add('nickname', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'pokemon_sheet.form.field.nickname',
                ],
            ])
            ->add('moveSetName', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'pokemon_sheet.form.field.moveSetName',
                ],
            ])
            ->add('ability', EntityType::class, [
                'label' => 'pokemon_sheet.form.field.ability',
                'class' => Ability::class,
                'choices' => $this->abilityRepository->getAbilitysByPokemon($pokemon->getPokemon()),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('move_1', EntityType::class, [
                'label' => 'pokemon_sheet.form.field.moves',
                'class' => Move::class,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'mapped' => false,
            ])
            ->add('move_2', EntityType::class, [
                'label' => false,
                'class' => Move::class,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'mapped' => false,
            ])
            ->add('move_3', EntityType::class, [
                'label' => false,
                'class' => Move::class,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'mapped' => false,
            ])
            ->add('move_4', EntityType::class, [
                'label' => false,
                'class' => Move::class,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'mapped' => false,
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
