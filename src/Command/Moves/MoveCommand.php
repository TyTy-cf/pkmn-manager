<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param MoveManager $moveManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        MoveManager $moveManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($moveManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:move:all to fetch all moves for language');
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
        $output->writeln('<info>Fetching all moves for pokemon...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllMoveJson()->toArray());
    }
}