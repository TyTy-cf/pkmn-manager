<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Users\LanguageManager;
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
     * ExcecCommand constructor
     * @param VersionManager $versionManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct
    (
        VersionManager $versionManager,
        LanguageManager $languageManager,
        ApiManager $apiManager
    )
    {
        parent::__construct($versionManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:version:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:version:all to fetch all version for language');
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
        $output->writeln('');
        $output->writeln('<info>Fetching all version...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllVersionJson()->toArray());
    }
}