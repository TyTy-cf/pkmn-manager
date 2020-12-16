<?php


namespace App\Controller\Moves;


use App\Entity\Versions\VersionGroup;
use App\Manager\Moves\MoveDescriptionManager;
use App\Manager\Moves\MoveMachineManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Moves\PokemonMovesLearnVersionManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoveController extends AbstractController
{

    /**
     * @var MoveDescriptionManager $moveDescriptionManager
     */
    private MoveDescriptionManager $moveDescriptionManager;

    /**
     * @var MoveMachineManager $moveMachineManager
     */
    private MoveMachineManager $moveMachineManager;

    /**
     * @var MoveManager $moveManager
     */
    private MoveManager $moveManager;

    /**
     * @var PokemonMovesLearnVersionManager $pmlvm
     */
    private PokemonMovesLearnVersionManager $pmlvm;

    /**
     * MoveController constructor.
     * @param MoveDescriptionManager $moveDescriptionManager
     * @param MoveMachineManager $moveMachineManager
     * @param MoveManager $moveManager
     * @param PokemonMovesLearnVersionManager $pmlvm
     */
    public function __construct(
        MoveDescriptionManager $moveDescriptionManager,
        MoveMachineManager $moveMachineManager,
        MoveManager $moveManager,
        PokemonMovesLearnVersionManager $pmlvm
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
