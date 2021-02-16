<?php


namespace App\Command\Pokedex;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokedex\EggGroupService;
use App\Service\Pokedex\EvolutionChainService;
use App\Service\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionChainCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param EvolutionChainService $evolutionChainManager
     * @param LanguageManager $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EvolutionChainService $evolutionChainManager,
        LanguageManager $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($evolutionChainManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:evolution:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:evolution:all to fetch all evolution for language');
    }

    /**
     * Execute the command to fetch all natures
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all evolution...');

        return $this->executeFromManager($input, $output, $this->apiManager->getEvolutionChainJson()->toArray());
    }
}
