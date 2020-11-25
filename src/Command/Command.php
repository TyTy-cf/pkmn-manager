<?php

namespace App\Command;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Pokemon manager
 */

class PokemonCommand extends Command
{
    /**
     * Name of the command (after bin/console)
     */
    protected static $defaultName = "app:pokemon:all";

    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ApiManager $apiManager;
     */
    private ApiManager $apiManager;

    /**
     * ExcecCommand constructor
     * @param PokemonManager $pokemonManager
     * @param ApiManager $apiManager
     */
    public function __construct(PokemonManager $pokemonManager, ApiManager $apiManager)
    {
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon:all fr')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon');
    }

    /**
     * Execute app:pokemon:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        //Get list of pokemons
        $apiReponse = $this->apiManager->getPokemonJson();
        $allPokemons = $apiReponse->toarray();

        //Initialise progress bar
        $progressBar = new ProgressBar($output, count($allPokemons['results']));
        $progressBar->start();

        // Check row
        foreach ($allPokemons['results'] as $pokemon ) {

            $namePokemon = $pokemon['name'];

            //Save pokemon in BDD
            $apiReponse = $this->apiManager->getPokemonFromName($namePokemon);
            $detailledPokemon = $apiReponse->toarray();

            $pokemonSaved = $this->pokemonManager->saveNewPokemon($detailledPokemon, $namePokemon);

            $progressBar->advance();
        }

        $progressBar->finish();

        return command::SUCCESS;
    }



}