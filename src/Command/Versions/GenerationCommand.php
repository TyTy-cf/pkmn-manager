<?php


namespace App\Command\Versions;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Users\LanguageManager;
use App\Manager\Versions\GenerationManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerationCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param GenerationManager $generationManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct
    (
        GenerationManager $generationManager,
        LanguageManager $languageManager,
        ApiManager $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($generationManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:generation:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:generation:all to fetch all generations for language');
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
        $output->writeln('');
        $output->writeln('<info>Fetching all generations...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllGenerationJson()->toArray());
    }
}