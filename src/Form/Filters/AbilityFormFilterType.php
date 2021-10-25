<?php


namespace App\Form\Filters;


use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbilityFormFilterType.php
 *
 * @author Kevin Tourret
 */
class AbilityFormFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])
            ->add('description', TextFilterType::class, [
                'condition_pattern' => FilterOperands::STRING_CONTAINS,
            ])
        ;
    }
}
