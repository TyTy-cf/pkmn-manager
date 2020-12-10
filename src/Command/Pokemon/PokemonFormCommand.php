<?php


namespace App\Command\Pokemon;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Pokemon\PokemonFormManager;
use App\Manager\Pokemon\PokemonManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PokemonFormCommand extends AbstractCommand
{
    /**
     * @var PokemonManager $pokemonManager
     */
    private PokemonManager $pokemonManager;

    /**
     * ExcecCommand constructor
     * @param PokemonFormManager $pokemonFormManager
     * @param PokemonManager $pokemonManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        PokemonFormManager $pokemonFormManager,
        PokemonManager $pokemonManager,
        LanguageManager $languageManager,
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        $this->pokemonManager = $pokemonManager;
        parent::__construct($pokemonFormManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:pokemon-form:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all pokemon for language');
    }

    /**
     * Execute app:pokemon:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all pokemon-form...');

        $lang = $input->getArgument('lang');

        if ($this->checkLanguageExists($output, $lang))
        {
            $language = $this->languageManager->getLanguageByCode($lang);
            $arrayPokemons = $this->pokemonManager->getAllPokemonByLanguage($language);

            //Initialise progress bar
            $progressBar = new ProgressBar($output, sizeof($arrayPokemons));
            $progressBar->start();

            foreach ($arrayPokemons as $pokemon) {
                $this->manager->createPokemonFormFromPokemon($pokemon, $language);
                $progressBar->advance();
            }

            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;

    }

}
