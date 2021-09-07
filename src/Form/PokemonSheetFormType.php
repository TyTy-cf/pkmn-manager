<?php


namespace App\Form;


use App\Entity\Infos\Ability;
use App\Entity\Infos\Gender;
use App\Entity\Infos\Nature;
use App\Entity\Pokemon\Pokemon;
use App\Entity\Pokemon\PokemonSheet;
use App\Repository\Infos\GenderRepository;
use App\Repository\Infos\NatureRepository;
use App\Repository\Infos\PokemonAbilityRepository;
use App\Repository\Pokemon\PokemonRepository;
use App\Repository\Users\LanguageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /**
     * PokemonSheetFormType constructor.
     * @param PokemonRepository $pokemonRepository
     * @param LanguageRepository $languageRepository
     * @param GenderRepository $genderRepository
     * @param NatureRepository $natureRepository
     */
    public function __construct(
        PokemonRepository $pokemonRepository,
        LanguageRepository $languageRepository,
        GenderRepository $genderRepository,
        NatureRepository $natureRepository
    ) {
        $this->pokemonRepository = $pokemonRepository;
        $this->languageRepository = $languageRepository;
        $this->genderRepository = $genderRepository;
        $this->natureRepository = $natureRepository;
    }

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
                'choices' => $this->natureRepository->findBy(['language' => $this->languageRepository->findOneBy(['code' => 'fr'])], ['name' => 'ASC']),
                'choice_label' => 'name',
                'choice_value' => 'id',
            ])
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
            ->add('level', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'pokemon_sheet.form.field.level',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'pokemon_sheet.form.submit',
                'attr' => [
                    'class' => 'btn btn-primary ml-1 btn-search-pokemon',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PokemonSheet::class,
        ]);
    }
}
