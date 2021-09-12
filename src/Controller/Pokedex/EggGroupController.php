<?php


namespace App\Controller\Pokedex;


use App\Repository\Pokedex\EggGroupRepository;
use Doctrine\ORM\NonUniqueResultException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EggGroupController extends AbstractController
{
    /**
     * Display the last pokemon add in the database
     *
     * @Route (path="{code}/groupe-oeuf/{slug}", name="egg_group_detail")
     *
     * @param string $code
     * @param string $slug
     * @param EggGroupRepository $eggGroupRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function index(string $code, string $slug, EggGroupRepository $eggGroupRepository): Response {
        $eggGroup = $eggGroupRepository->findOneBySlugWithRelation($slug, $code);
        return $this->render('EggGroup/detail.html.twig', [
            'eggGroup' => $eggGroup,
        ]);
    }
}
