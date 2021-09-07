<?php


namespace App\Controller\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Form\PokemonSheetAbilityType;
use App\Form\PokemonSheetFormType;
use App\Repository\Infos\PokemonAbilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokemonSheetController.php
 *
 * @author Kevin Tourret
 * @property PokemonAbilityRepository $pokemonAbilityRepository
 */
class PokemonSheetController extends AbstractController
{

    /**
     * PokemonSheetController constructor.
     * @param PokemonAbilityRepository $pokemonAbilityRepository
     */
    public function __construct(PokemonAbilityRepository $pokemonAbilityRepository)
    {
        $this->pokemonAbilityRepository = $pokemonAbilityRepository;
    }

    /**
     * @Route(path="/creer/pokemon", name="pokemon_create")
     *
     * @param Request $request
     * @return Response
     */
    public function addPokemonSheet(Request $request): Response {
        $pokemonSheet = new PokemonSheet();
        $form = $this->createForm(PokemonSheetFormType::class, $pokemonSheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $pokemonSheet->setAbility(
                $this->pokemonAbilityRepository->findBy(['pokemon' => $pokemonSheet->getPokemon()])[0]->getAbility()
            );
            $entityManager->persist($pokemonSheet);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_sheet_show', ['id' => $pokemonSheet->getId()]);
        }

        return $this->render('Pokemon/Pokemon_sheet/pokemon_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/fiche_pokemon/{id}", name="pokemon_sheet_show")
     *
     * @param Request $request
     * @param PokemonSheet $pokemonSheet
     * @return Response
     */
    public function show(Request $request, PokemonSheet $pokemonSheet): Response {

        return $this->render('Pokemon/Pokemon_sheet/fiche_pokemon_show.html.twig', [
            'pokemonSheet' => $pokemonSheet,
            'abilities' => $this->pokemonAbilityRepository->findBy(['pokemon' => $pokemonSheet->getPokemon()]),
        ]);
    }
}
