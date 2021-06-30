<?php


namespace App\Controller\Infos;


use App\Repository\Infos\NatureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NatureController.php
 *
 * @author Kevin Tourret
 *
 * @property NatureRepository $natureRepository
 */
class NatureController extends AbstractController
{

    /**
     * NatureController constructor.
     * @param NatureRepository $natureRepository
     */
    public function __construct(NatureRepository $natureRepository)
    {
        $this->natureRepository = $natureRepository;
    }

    /**
     * @Route (path="/nature", name="nature_index")
     * @return Response
     */
    public function displayNatures(): Response {
        return $this->render('Nature/index.html.twig', [
            'natures' => $this->natureRepository->findAll(),
        ]);
    }
}
