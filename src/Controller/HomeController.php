<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route(path="/", name="home")
     *
     * @param Request $request
     * @return Response
     */
    public function redirectHome(Request $request): Response {
        return $this->redirectToRoute('generation_index', ['code' => 'fr']);
    }

}
