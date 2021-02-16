<?php


namespace App\Command\Pokedex;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Pokedex\EvolutionTriggerService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EvolutionTriggerCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param EvolutionTriggerService $evolutionTriggerManager
     * @param LanguageService $languageManager
     * @param ApiService $apiManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        EvolutionTriggerService $evolutionTriggerManager,
        LanguageService $languageManager,
        ApiService $apiManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($evolutionTriggerManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:evolution-trigger:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:evolution-trigger:all to fetch all evolution triggers for language');
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
        $output->writeln('<info>Fetching all evolution triggers...');

        return $this->executeFromManager($input, $output, $this->apiManager->getEvolutionTriggerJson()->toArray());
    }
}
