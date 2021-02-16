<?php


namespace App\Command\Pokemon;

use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokemon\PokemonSpeciesService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonSpeciesCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param PokemonSpeciesService $pokemonSpeciesManager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        PokemonSpeciesService $pokemonSpeciesManager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($pokemonSpeciesManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon-species:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:sprites to fetch sprites for all pokemons by version');
    }

    /**
     * Execute app:pokemon-species:all
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all pokemon species...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllPokemonSpeciesJson()->toArray());
    }

}