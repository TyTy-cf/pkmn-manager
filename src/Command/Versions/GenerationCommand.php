<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Versions\GenerationManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationCommand extends AbstractCommand
{

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var GenerationManager $generationManager
     */
    private GenerationManager $generationManager;

    /**
     * ExcecCommand constructor
     * @param GenerationManager $generationManager
     * @param ApiManager $apiManager
     */
    public function __construct(GenerationManager $generationManager,
                                ApiManager $apiManager)
    {
        $this->generationManager = $generationManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:generation:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');

        $output->writeln('');
        $output->writeln('<info>Fetching all generation for language ' . $lang . '</info>');

        //Get list of types
        $generationList = $this->apiManager->getAllGenerationJson()->toArray();

        //Initialize progress bar
        $progressBar = new ProgressBar($output, count($generationList['results']));
        $progressBar->start();

        foreach ($generationList['results'] as $generation) {
            $this->generationManager->createGenerationIfNotExist($lang, $generation);
            $progressBar->advance();
        }

        //End of the progressBar
        $progressBar->finish();

        return command::SUCCESS;
    }
}