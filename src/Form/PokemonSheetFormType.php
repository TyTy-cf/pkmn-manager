<?php


namespace App\Form;


use App\Entity\Infos\Gender;
use App\Entity\Infos\Nature;
use App\Entity\Pokemon\Pokemon;
use App\Repository\Infos\GenderRepository;
use App\Repository\Infos\NatureRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Users\LanguageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PokemonSheetFormType.php
 *
 * @author Kevin Tourret
 *
 * @property PokemonRepository $pokemonRepository
 * @property LanguageRepository $languageRepository
 * @property GenderRepository $genderRepository
 * @property NatureRepository $natureRepository
 */
class PokemonSheetFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pokemon', EntityType::class, [
                'label' => false,
                'class' => Pokemon::class,
                'choices' => $this->pokemonRepository->getAllPokemonByLanguage($this->languageRepository->findOneBy(['code' => 'fr'])),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('nickname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nickname',
                ],
            ])
            ->add('level', NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Level',
                ],
            ])
            ->add('gender', EntityType::class, [
                'label' => false,
                'class' => Gender::class,
                'choices' => $this->genderRepository->findBy(['language' => $this->languageRepository->findOneBy(['code' => 'fr'])]),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('nature', EntityType::class, [
                'label' => false,
                'class' => Nature::class,
                'choices' => $this->natureRepository->findBy(['language' => $this->languageRepository->findOneBy(['code' => 'fr'])]),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
            ->add('submit', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-primary ml-1 btn-search-pokemon',
                ],
            ]);
    }
}
