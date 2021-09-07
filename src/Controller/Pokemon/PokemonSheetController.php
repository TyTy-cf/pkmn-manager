<?php


namespace App\Controller\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Form\PokemonSheetFormType;
use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\PokemonAbilityRepository;
use App\Repository\Pokemon\PokemonSheetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PokemonSheetController.php
 *
 * @author Kevin Tourret
 *
 * @property PokemonAbilityRepository $pokemonAbilityRepository
 * @property PokemonSheetRepository $pokemonSheetRepository
 * @property AbilityRepository $abilityRepository
 */
class PokemonSheetController extends AbstractController
{

    /**
     * PokemonSheetController constructor.
     * @param PokemonAbilityRepository $pokemonAbilityRepository
     * @param PokemonSheetRepository $pokemonSheetRepository
     * @param AbilityRepository $abilityRepository
     */
    public function __construct(
        PokemonAbilityRepository $pokemonAbilityRepository,
        PokemonSheetRepository $pokemonSheetRepository,
        AbilityRepository $abilityRepository
    ) {
        $this->pokemonAbilityRepository = $pokemonAbilityRepository;
        $this->pokemonSheetRepository = $pokemonSheetRepository;
        $this->abilityRepository = $abilityRepository;
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

    /**
     * @Route(path="/pokemonSheet/changeAbility/{datas}", name="change_ability")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeAbility(Request $request): JsonResponse {
        $json = json_decode($request->get('datas'), true);
        $pokemonSheet = $this->pokemonSheetRepository->findOneBy(['id' => $json['pokemonSheetId']]);
        $ability = $this->abilityRepository->findOneBy(['id' => $json['selectedAbilityId']]);
        $pokemonSheet->setAbility($ability);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pokemonSheet);
        $entityManager->flush();

        return (new JsonResponse())->setData([
            $data = [
                'html' => $this->renderView('Pokemon/Pokemon_sheet/_pokemon_sheet_ability.html.twig', [
                    'ability' => $ability,
                ])
            ]
        ]);
    }
}
