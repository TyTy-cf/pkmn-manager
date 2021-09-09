<?php


namespace App\Controller\Moves;


use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoveController extends AbstractController
{

    /**
     * @Route(path="/attaque/{slug_move}", name="move_detail", requirements={"slug_move": ".+"})
     *
     * @param Request $request
     * @param MoveRepository $moveRepository
     * @param MoveMachineRepository $moveMachineRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(
        Request $request,
        MoveRepository $moveRepository,
        MoveMachineRepository $moveMachineRepository
    ): Response {
        $move = $moveRepository->getMoveBySlugWithRelation($request->get('slug_move'));
        return $this->render('Moves/detail.html.twig', [
            'move' => $move,
            'moveMachine' => $moveMachineRepository->getMoveMachineByMove($move),
        ]);
    }

}
