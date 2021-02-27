<?php


namespace App\Controller\Moves;


use App\Service\Moves\MoveDescriptionService;
use App\Service\Moves\MoveMachineService;
use App\Service\Moves\MoveService;
use App\Service\Moves\PokemonMovesLearnVersionService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoveController extends AbstractController
{

    /**
     * @var MoveDescriptionService $moveDescriptionManager
     */
    private MoveDescriptionService $moveDescriptionManager;

    /**
     * @var MoveMachineService $moveMachineManager
     */
    private MoveMachineService $moveMachineManager;

    /**
     * @var MoveService $moveManager
     */
    private MoveService $moveManager;

    /**
     * @var PokemonMovesLearnVersionService $pmlvm
     */
    private PokemonMovesLearnVersionService $pmlvm;

    /**
     * MoveController constructor.
     * @param MoveDescriptionService $moveDescriptionManager
     * @param MoveMachineService $moveMachineManager
     * @param MoveService $moveManager
     * @param PokemonMovesLearnVersionService $pmlvm
     */
    public function __construct(
        MoveDescriptionService $moveDescriptionManager,
        MoveMachineService $moveMachineManager,
        MoveService $moveManager,
        PokemonMovesLearnVersionService $pmlvm
    )
    {
        $this->moveDescriptionManager = $moveDescriptionManager;
        $this->moveMachineManager = $moveMachineManager;
        $this->pmlvm = $pmlvm;
        $this->moveManager = $moveManager;
    }

    /**
     * @Route(path="/move/{slug_move}", name="move_detail", requirements={"slug_move": ".+"})
     *
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function indexMove(Request $request) {
        $move = $this->moveManager->getSimpleMoveBySlug($request->get('slug_move'));
        return $this->render('Moves/detail.html.twig', [
            'move' => $move,
            'moveDescription' => $this->moveDescriptionManager->getMoveDescriptionByMove($move),
            'moveMachine' => $this->moveMachineManager->getMoveMachineByMove($move),
            'moveLearnPokemon' => $this->pmlvm->getMoveLearnByPokemon($move),
        ]);
    }

}
