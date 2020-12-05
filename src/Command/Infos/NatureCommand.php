<?php


namespace App\Command\Infos;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\NatureManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NatureCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param NatureManager $natureManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        NatureManager $natureManager,
        LanguageManager $languageManager,
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($natureManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:nature:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:nature:all to fetch all natures for language');
    }

    /**
     * Execute the command to fetch all natures
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all natures...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllNatureJson()->toArray());
    }
}