<?php


namespace App\Form;


use App\Repository\Infos\AbilityRepository;
use App\Repository\Moves\MoveRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PokemonSheetPokemonFormType.php
 *
 * @author Kevin Tourret
 *
 * @property MoveRepository $moveRepository
 * @property AbilityRepository $abilityRepository
 */
class PokemonSheetMoveFormType extends AbstractType
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
            ->add('moves', CollectionType::class, [
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'add_btn_icon' => 'fas fa-plus-circle',
                'add_btn_class' => 'btn pokemon-sheet-add-button mr-1',
                'add_btn_label' => false,
                'add_btn_label_translation_domain' => false,
                'delete_btn_class' => 'btn pokemon-sheet-cancel-button mx-1 mb-4',
                'delete_btn_label' => false,
                'delete_btn_icon' => 'fas fa-trash-alt',
                'delete_btn_label_translation_domain' => false,
                'attr' => [
                    'class' => 'mt-2 mb-2',
                ],
                'entry_type' => MoveFormType::class,
                'entry_options' => [
                    'data' => $moves,
                    'collapsable' => true,
                    'attr' => [
                        'data-form-collapsable' => false,
                        'class' => 'col-6',
                    ],
                    'label' => false,
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'pokemon_sheet.form.add',
                'attr' => [
                    'class' => 'btn btn-primary ml-1 btn-search-pokemon',
                ],
            ])
        ;
    }

}
