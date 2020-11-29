<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Versions\VersionManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionCommand extends AbstractCommand
{

    /**
     * @var ApiManager $apiManager ;
     */
    private ApiManager $apiManager;

    /**
     * @var VersionManager $versionManager
     */
    private VersionManager $versionManager;

    /**
     * ExcecCommand constructor
     * @param VersionManager $versionManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        VersionManager $versionManager,
        ApiManager $apiManager
    )
    {
        $this->versionManager = $versionManager;
        $this->apiManager = $apiManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:version:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface|NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Fetch parameter
        $lang = $input->getArgument('lang');
        if ($this->checkLanguageExists($output, $lang)) {

            $output->writeln('');
            $output->writeln('<info>Required : VersionGroup. Fetching all version-group for language ' . $lang . '</info>');
            $this->executeCommand($output, $lang, 'app:version-group:all');

            $output->writeln('');
            $output->writeln('<info>Fetching all version for language ' . $lang . '</info>');

            //Get list of types
            $versionList = $this->apiManager->getAllVersionJson()->toArray();

            //Initialize progress bar
            $progressBar = new ProgressBar($output, count($versionList['results']));
            $progressBar->start();

            foreach ($versionList['results'] as $version) {
                $this->versionManager->createVersionIfNotExist($lang, $version);
                $progressBar->advance();
            }

            //End of the progressBar
            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }
}