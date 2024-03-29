<?php


namespace App\Controller\Front\Pokemon;


use App\Entity\Pokemon\PokemonSheet;
use App\Entity\Stats\Stats;
use App\Entity\Stats\StatsEv;
use App\Form\PokemonSheet\PokemonSheetFormType;
use App\Form\PokemonSheet\PokemonSheetMoveFormType;
use App\Form\PokemonSheet\PokemonSheetStatsFormType;
use App\Repository\Infos\AbilityRepository;
use App\Repository\Infos\PokemonAbilityRepository;
use App\Repository\Pokemon\PokemonSheetRepository;
use App\Service\Pokemon\StatsCalculatorService;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\PaginatorInterface;
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
 * @property StatsCalculatorService $statsCalculatorService
 */
class PokemonSheetController extends AbstractController
{

    /**
     * PokemonSheetController constructor.
     * @param PokemonAbilityRepository $pokemonAbilityRepository
     * @param PokemonSheetRepository $pokemonSheetRepository
     * @param AbilityRepository $abilityRepository
     * @param StatsCalculatorService $statsCalculatorService
     */
    public function __construct(
        PokemonAbilityRepository $pokemonAbilityRepository,
        PokemonSheetRepository $pokemonSheetRepository,
        AbilityRepository $abilityRepository,
        StatsCalculatorService $statsCalculatorService
    ) {
        $this->pokemonAbilityRepository = $pokemonAbilityRepository;
        $this->pokemonSheetRepository = $pokemonSheetRepository;
        $this->abilityRepository = $abilityRepository;
        $this->statsCalculatorService = $statsCalculatorService;
    }

    /**
     * @Route(path="{code}/mes-pokemons", name="my_pokemons")
     *
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param string $code
     * @return Response
     */
    public function myPokemons(Request $request, PaginatorInterface $paginator, string $code): Response {
        $pokemonsQuery = $this->pokemonSheetRepository->findAllWithRelations();

        $pokemons = $paginator->paginate(
            $pokemonsQuery,
            $request->query->getInt('page', 1),
            6,
            [
                'wrap-queries' => true,
            ]
        );

        return $this->render('Pokemon/Pokemon_sheet/pokemon_index.html.twig', [
            'pokemonsSheet' => $pokemons,
            'code' => $code,
        ]);
    }

    /**
     * @Route(path="{code}/mes-pokemons/ajouter", name="pokemon_create")
     *
     * @param Request $request
     * @param string $code
     * @return Response
     */
    public function addPokemonSheet(Request $request, string $code): Response {
        $pokemonSheet = new PokemonSheet();
        $form = $this->createForm(PokemonSheetFormType::class, $pokemonSheet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $pokemonSheet->setAbility(
                $this->pokemonAbilityRepository->findBy(['pokemon' => $pokemonSheet->getPokemon()])[0]->getAbility()
            );
            $evs = (new StatsEv())->setHp(0)->setAtk(0)->setDef(0)->setSpa(0)->setSpd(0)->setSpe(0);
            $stats = (new Stats())->setHp(0)->setAtk(0)->setDef(0)->setSpa(0)->setSpd(0)->setSpe(0);
            $entityManager->persist($evs);
            $entityManager->persist($stats);
            $pokemonSheet->setEvs($evs);
            $pokemonSheet->setStats($stats);
            $entityManager->persist($pokemonSheet);
            $entityManager->flush();

            return $this->redirectToRoute('pokemon_sheet_show', ['code' => $code, 'id' => $pokemonSheet->getId()]);
        }

        return $this->render('Pokemon/Pokemon_sheet/pokemon_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="{code}/mes-pokemons/detail/{id}", name="pokemon_sheet_show")
     *
     * @param Request $request
     * @param string $code
     * @return Response
     * @throws NonUniqueResultException
     */
    public function show(Request $request, string $code): Response {
        $pokemonSheet = $this->pokemonSheetRepository->findByIdWithRelations($request->get('id'));

        $form = $this->createForm(PokemonSheetMoveFormType::class, $pokemonSheet);
        $form->handleRequest($request);
        $formStats =  $this->createForm(PokemonSheetStatsFormType::class, $pokemonSheet);
        $formStats->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        if ($formStats->isSubmitted() && $formStats->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // Calculate the IVs
            $statsIvs = $this->statsCalculatorService->getIvs($pokemonSheet);
            if ($pokemonSheet->getIvs() === null) {
                $entityManager->persist($statsIvs);
            }
            $pokemonSheet->setIvs($statsIvs);
            $entityManager->flush();
        }

        return $this->render('Pokemon/Pokemon_sheet/fiche_pokemon_show.html.twig', [
            'pokemonSheet' => $pokemonSheet,
            'abilities' => $this->pokemonAbilityRepository->findBy(['pokemon' => $pokemonSheet->getPokemon()]),
            'form' => $form->createView(),
            'formStats' => $formStats->createView(),
            'code' => $code,
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
        $ability = $this->abilityRepository->findOneBy(['id' => $json['inputData']]);
        $pokemonSheet->setAbility($ability);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return (new JsonResponse())->setData(
            $data = [
                'html' => $this->renderView('Pokemon/Pokemon_sheet/partials/_pokemon_sheet_ability.html.twig', [
                    'ability' => $ability,
                    'code' => $ability->getLanguage()->getCode(),
                ])
            ]
        );
    }

    /**
     * @Route(path="/pokemonSheet/changeMoveSet/{datas}", name="change_ability")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeMoveSet(Request $request): JsonResponse {
        $json = json_decode($request->get('datas'), true);
        $pokemonSheet = $this->pokemonSheetRepository->findOneBy(['id' => $json['pokemonSheetId']]);
        $pokemonSheet->setMoveSetName($json['inputData']);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        return (new JsonResponse())->setData(
            $data = [
                'html' => $pokemonSheet->getMoveSetName(),
           ]
        );
    }

}
