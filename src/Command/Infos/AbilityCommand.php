<?php


namespace App\Command\Infos;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\AbilitiyManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilityCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param AbilitiyManager $abilitiesManager
     * @param LanguageManager $languageManager
     * @param ApiManager $apiManager
     */
    public function __construct(AbilitiyManager $abilitiesManager,
                                LanguageManager $languageManager,
                                ApiManager $apiManager)
    {
        parent::__construct($abilitiesManager, $languageManager, $apiManager);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:ability:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:ability:all to fetch all abilities for the required language');
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
        $output->writeln('<info>Fetching all abilities...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllAbilitiesJson()->toArray());
    }
}