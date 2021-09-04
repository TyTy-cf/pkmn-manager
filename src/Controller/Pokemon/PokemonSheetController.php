<?php


namespace App\Controller\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Form\PokemonSheetFormType;
use App\Form\PokemonSheetPokemonFormType;
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
            $entityManager->persist($pokemonSheet);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_create_next', ['id' => $pokemonSheet->getId()]);
        }

        return $this->render('Pokemon/Pokemon_sheet/pokemon_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/creer/pokemon/{id}", name="pokemon_create_next")
     *
     * @param Request $request
     * @param PokemonSheet $pokemonSheet
     * @return Response
     */
    public function addPokemonSheetById(Request $request, PokemonSheet $pokemonSheet): Response {
        $form = $this->createForm(PokemonSheetPokemonFormType::class, $pokemonSheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            for($i = 1; $i < 5; $i++) {
                $pokemonSheet->addMove($form['move_' . $i]->getData());
            }
            $entityManager->persist($pokemonSheet);
            $entityManager->flush();
            return $this->redirectToRoute('pokemon_show', ['id' => $pokemonSheet->getId()]);
        }

        return $this->render('Pokemon/Pokemon_sheet/pokemon_add_moves.html.twig', [
            'form' => $form->createView(),
            'pokemonSheet' => $pokemonSheet,
        ]);
    }

    /**
     * @Route(path="/fiche_pokemon/{id}", name="pokemon_show")
     *
     * @param Request $request
     * @param PokemonSheet $pokemonSheet
     * @return Response
     */
    public function show(Request $request, PokemonSheet $pokemonSheet): Response {
        return $this->render('Pokemon/Pokemon_sheet/fiche_pokemon_show.html.twig', [
            'pokemonSheet' => $pokemonSheet,
        ]);
    }
}
