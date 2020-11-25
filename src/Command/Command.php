<?php

namespace App\Command;

use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Pokemon manager
 */

class ExcecCommand extends Command
{
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
            ->setName('app:pokemon:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon');
    }

    /**
     * Execute app:pokemon:all
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lang = $input->getArgument('lang');

//        if (null === $lang = $this->apiManager->getPokemonFromName());
    }

}