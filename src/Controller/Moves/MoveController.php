<?php


namespace App\Controller\Moves;


use App\Repository\Moves\MoveMachineRepository;
use App\Repository\Moves\MoveRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MoveController
 * @package App\Controller\Moves
 *
 * @property MoveRepository $moveRepository,
 * @property MoveMachineRepository $moveMachineRepository
 */
class MoveController extends AbstractController
{

    /**
     * MoveController constructor.
     * @param MoveRepository $moveRepository
     * @param MoveMachineRepository $moveMachineRepository
     */
    public function __construct(MoveRepository $moveRepository, MoveMachineRepository $moveMachineRepository)
    {
        $this->moveRepository = $moveRepository;
        $this->moveMachineRepository = $moveMachineRepository;
    }

    /**
     * @Route(path="{code}/attaque/{slug}", name="move_detail")
     *
     * @param string $code
     * @param string $slug
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(string $code, string $slug): Response {
        $move = $this->moveRepository->getMoveBySlugWithRelation($slug, $code);
        return $this->render('Moves/detail.html.twig', [
            'move' => $move,
            'moveMachine' => $this->moveMachineRepository->getMoveMachineByMove($move),
        ]);
    }

}
