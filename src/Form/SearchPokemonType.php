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
            ->add('namePokemon', SearchType::class, [
                'label' => 'Rechercher un pokemon : ',
                'attr' => [
                    'maxlenght' => 15,
                    'autocomplete' => 'off',
                ]
            ])
            ->add('Rechercher', SubmitType::class);
    }
}