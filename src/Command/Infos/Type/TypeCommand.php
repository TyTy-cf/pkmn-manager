<?php


namespace App\Command\Infos\Type;


use App\Command\AbstractCommand;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Users\LanguageManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeCommand extends AbstractCommand
{

    /**
     * ExcecCommand constructor
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     */
    public function __construct(
        TypeManager $typeManager,
        ApiManager $apiManager,
        LanguageManager $languageManager
    ) {
        parent::__construct($typeManager, $languageManager, $apiManager);
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