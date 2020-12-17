<?php


namespace App\Controller\Versions;

use App\Manager\Users\LanguageManager;
use App\Manager\Versions\GenerationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerationController extends AbstractController
{

    /**
     * @var GenerationManager $generationManager
     */
    private GenerationManager $generationManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * PokemonController constructor.
     *
     * @param GenerationManager $generationManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        GenerationManager $generationManager,
        LanguageManager $languageManager
    )
    {
        $this->generationManager = $generationManager;
        $this->languageManager = $languageManager;
    }

    /**
     * @Route(path="/", name="generation_index")
     *
     * @param Request $request
     * @return Response
     */
    public function generationIndex(Request $request): Response {
        return $this->render('Versions/generation_index.html.twig', [
            'generationList' => $this->generationManager->getAllGenerationsByLanguage(
                $this->languageManager->getLanguageByCode('fr')
            ),
        ]);
    }

}
