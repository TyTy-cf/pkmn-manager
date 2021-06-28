<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchPokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_pokemon', SearchType::class, [
                'label' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => 'Pokemon...',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-primary ml-1 fas fa-arrow-circle-right btn-search-pokemon',
                ],
            ]);
    }
}
