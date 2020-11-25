<?php

namespace App\Command\Pokemon;

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
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * @var ApiManager $apiManager ;
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
            ->setName('app:pokemon:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * Execute app:pokemon:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');

        //Get list of pokemons
        $apiResponse = $this->apiManager->getPokemonJson();
        $allPokemons = $apiResponse->toarray();

        //Initialise progress bar
        $progressBar = new ProgressBar($output, count($allPokemons['results']));
        $progressBar->start();

        // Check row
        foreach ($allPokemons['results'] as $pokemon) {

            $namePokemon = $pokemon['name'];

            //Save pokemon in BDD
            $apiResponse = $this->apiManager->getPokemonFromName($namePokemon);
            $detailedPokemon = $apiResponse->toarray();

            $this->pokemonManager->saveNewPokemon($lang, $detailedPokemon, $namePokemon);

            //Advance progressBar
            $progressBar->advance();
        }

        $progressBar->finish();

        return command::SUCCESS;
    }
}