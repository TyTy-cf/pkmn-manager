<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Moves\MoveMachineService;
use App\Service\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveMachineCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param MoveMachineService $moveMachineManager
     * @param ApiService $apiManager
     * @param LanguageManager $languageManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        MoveMachineService $moveMachineManager,
        ApiService $apiManager,
        LanguageManager $languageManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($moveMachineManager, $languageManager, $apiManager, $em);
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