<?php

namespace App\Command;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Pokemon manager
 */

class ExcecCommand extends Command
{
    /**
     * Name of the command (after bin/console)
     */
    protected static $defaultName = "app:pokemon:all fr";

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
        parent::__construct();
        $this->pokemonManager = $pokemonManager;
        $this->apiManager = $apiManager;
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
        $apiReponse = $this->apiManager->getPokemonJson();

        $allPokemons = $apiReponse->toarray();

        foreach ($allPokemons['results'] as $pokemon ) {

            $namePokemon = $pokemon['name'];

            $apiReponse = $this->apiManager->getPokemonFromName($namePokemon);

            $pokemonSaved = $this->pokemonManager->saveNewPokemon($apiReponse, $namePokemon);
        }

        return command::SUCCESS;
    }

}