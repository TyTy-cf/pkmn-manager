<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\VersionGroupManager;
use Exception;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class VersionGroupCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param VersionGroupManager $versionGroupManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        VersionGroupManager $versionGroupManager,
        LanguageManager $languageManager,
        ApiManager $apiManager
    )
    {
        parent::__construct($versionGroupManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:version-group:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:version-group:all to fetch all version-group for language');
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
        $output->writeln('');
        $output->writeln('<info>Fetching all version-group...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllVersionGroupJson()->toArray());
    }
}