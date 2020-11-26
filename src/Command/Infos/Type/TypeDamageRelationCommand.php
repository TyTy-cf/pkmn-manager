<?php


namespace App\Command\Infos\Type;


use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeDamageRelationManager;
use App\Manager\Infos\Type\TypeManager;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TypeDamageRelationCommand extends Command
{

    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var TypeDamageRelationManager
     */
    private TypeDamageRelationManager $typeDamageFromTypeManager;

    /**
     * ExcecCommand constructor
     *
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     * @param TypeDamageRelationManager $typeDamageFromTypeManager
     */
    public function __construct(
        TypeManager $typeManager,
        ApiManager $apiManager,
        TypeDamageRelationManager $typeDamageFromTypeManager
    ) {
        $this->apiManager = $apiManager;
        $this->typeManager = $typeManager;
        $this->typeDamageFromTypeManager = $typeDamageFromTypeManager;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:damage-relation:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetch all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws TransportExceptionInterface
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Fetch all Type by language
        $lang = $input->getArgument('lang');
        $types = $this->typeManager->getAllTypeByLanguage($lang);

        //Initialise progress bar
        $progressBar = new ProgressBar($output, sizeof($types));
        $progressBar->start();

        foreach($types as $type)
        {
            $this->typeDamageFromTypeManager->createDamageFromType($type, $lang);
            $progressBar->advance();
        }

        $progressBar->finish();

        return command::SUCCESS;
    }

}