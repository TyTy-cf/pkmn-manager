<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveMachineManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveMachineCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param MoveMachineManager $moveMachineManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct
    (
        MoveMachineManager $moveMachineManager,
        ApiManager $apiManager,
        LanguageManager $languageManager
    )
    {
        parent::__construct($moveMachineManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move-machine:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:move-machine:all to fetch all moves for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all moves-machine...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllMoveMachineJson()->toArray());
    }
}