<?php


namespace App\Command\Infos\Type;


use App\Command\AbstractCommand;
use App\Service\Api\ApiService;
use App\Service\Infos\Type\TypeService;
use App\Service\Users\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param TypeService $typeManager
     * @param ApiService $apiManager
     * @param LanguageService $languageManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TypeService $typeManager,
        ApiService $apiManager,
        LanguageService $languageManager,
        EntityManagerInterface $em
    )
    {
        parent::__construct($typeManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:type:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:type:all to fetch all type for language');
    }

    /**
     * Execute the command app:type:all
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');
        $output->writeln('<info>Fetching all types...');

        return $this->executeFromManager($input, $output, $this->apiManager->getAllTypeJson()->toArray());
    }
}