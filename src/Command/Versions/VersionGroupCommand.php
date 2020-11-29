<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Versions\VersionGroupManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionGroupCommand extends AbstractCommand
{

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var VersionGroupManager $versionGroupManager
     */
    private VersionGroupManager $versionGroupManager;

    /**
     * ExcecCommand constructor
     * @param VersionGroupManager $versionGroupManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        VersionGroupManager $versionGroupManager,
        ApiManager $apiManager
    )
    {
        $this->versionGroupManager = $versionGroupManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:version-group:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');
        if ($this->checkLanguageExists($output, $lang)) {

            $output->writeln('');
            $output->writeln('<info>Required : Generation. Fetching all generation for language ' . $lang . '</info>');
            $this->executeCommand($output, $lang, 'app:generation:all');

            $output->writeln('');
            $output->writeln('<info>Fetching all version-group for language ' . $lang . '</info>');

            //Get list of types
            $versionGroupList = $this->apiManager->getAllVersionGroupJson()->toArray();

            //Initialize progress bar
            $progressBar = new ProgressBar($output, count($versionGroupList['results']));
            $progressBar->start();

            foreach ($versionGroupList['results'] as $versionGroup) {
                $this->versionGroupManager->createVersionGroupIfNotExist($lang, $versionGroup);
                $progressBar->advance();
            }

            //End of the progressBar
            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }
}