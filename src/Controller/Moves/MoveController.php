<?php


namespace App\Controller\Moves;


use App\Entity\Moves\Move;
use App\Manager\Moves\MoveDescriptionManager;
use App\Manager\Moves\MoveMachineManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * MoveController constructor.
     * @param MoveDescriptionManager $moveDescriptionManager
     * @param MoveMachineManager $moveMachineManager
     */
    public function __construct(MoveDescriptionManager $moveDescriptionManager, MoveMachineManager $moveMachineManager)
    {
        $this->moveDescriptionManager = $moveDescriptionManager;
        $this->moveMachineManager = $moveMachineManager;
    }

    /**
     * @Route(path="/move/{slug_move}", name="move_detail", requirements={"slug_move": ".+"})
     * @ParamConverter(class="App\Entity\Moves\Move", name="move", options={"mapping": {"slug_move" : "slug"}})
     *
     * @param Request $request
     * @param Move $move
     * @return Response
     */
    public function indexMove(Request $request, Move $move) {
        return $this->render('Moves/detail.html.twig', [
            'move' => $move,
            'moveDescription' => $this->moveDescriptionManager->getMoveDescriptionByMove($move),
            'moveMachine' => $this->moveMachineManager->getMoveMachineByMove($move),
        ]);
    }

}
