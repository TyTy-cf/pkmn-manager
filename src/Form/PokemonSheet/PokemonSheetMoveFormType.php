<?php


namespace App\Form\PokemonSheet;


use App\Entity\Moves\Move;
use App\Entity\Pokemon\PokemonSheet;
use App\Repository\Moves\MoveRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PokemonSheetPokemonFormType.php
 *
 * @author Kevin Tourret
 *
 * @property MoveRepository $moveRepository
 */
class PokemonSheetMoveFormType extends AbstractType
{

    /**
     * PokemonSheetMoveFormType constructor.
     * @param MoveRepository $moveRepository
     */
    public function __construct(MoveRepository $moveRepository)
    {
        $this->moveRepository = $moveRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pokemonSheet = $options['data'];
        $moves = $this->moveRepository->getMovesByPokemon($pokemonSheet->getPokemon());
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
                    'class' => 'my-2 mx-auto',
                ],
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'choices' => $moves,
                    'class' => Move::class,
                    'choice_label' => 'name',
                    'choice_value' => 'id',
                    'collapsable' => true,
                    'attr' => [
                        'data-form-collapsable' => false,
                        'class' => 'col-6',
                    ],
                    'label' => false,
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PokemonSheet::class,
        ]);
    }

}
