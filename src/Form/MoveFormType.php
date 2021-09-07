<?php


namespace App\Form;


use App\Entity\Moves\Move;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 *
 * @author Kevin Tourret
 */
class MoveFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $moves = $options['data'];
        $builder
            ->add('moves', EntityType::class, [
                'label' => false,
                'class' => Move::class,
                'choices' => $moves,
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
        ;
    }

}
