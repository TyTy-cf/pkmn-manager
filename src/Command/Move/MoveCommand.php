<?php


namespace App\Command\Move;


use App\Manager\Api\ApiManager;
use App\Manager\Moves\MoveManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveCommand extends Command
{

    /**
     * @var MoveManager $moveManager
     */
    private MoveManager $moveManager;

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager $languageManager
     */
    private LanguageManager $languageManager;

    /**
     * ExcecCommand constructor
     * @param MoveManager $moveManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct(MoveManager $moveManager,
                                ApiManager $apiManager,
                                LanguageManager $languageManager)
    {
        $this->moveManager = $moveManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:move:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');


        return command::SUCCESS;
    }
}