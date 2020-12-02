<?php


namespace App\Command\Moves;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveLearnMethodManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MoveLearnMethodCommand extends AbstractCommand
{
    /**
     * ExcecCommand constructor
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param MoveLearnMethodManager $moveLearnMethodManager
     */
    public function __construct(
        ApiManager $apiManager,
        LanguageManager $languageManager,
        MoveLearnMethodManager $moveLearnMethodManager
    )
    {
        parent::__construct($moveLearnMethodManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move-learn-method:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:move-learn-method:all to fetch all MoveLearnMethod for required language');
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
        $output->writeln('<info>Fetching all move-learn-method...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllMoveLearnMethodJson()->toArray());
    }

}