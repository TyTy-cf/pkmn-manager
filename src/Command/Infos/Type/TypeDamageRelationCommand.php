<?php


namespace App\Command\Infos\Type;


use App\Command\AbstractCommand;
use App\Entity\Infos\Type\TypeDamageRelationType;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeDamageRelationTypeManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Users\LanguageManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TypeDamageRelationCommand extends AbstractCommand
{

    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * TypeDamageRelationCommand constructor
     *
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     * @param LanguageManager $languageManager
     * @param TypeDamageRelationTypeManager $typeDamageFromTypeManager
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TypeManager $typeManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        TypeDamageRelationTypeManager $typeDamageFromTypeManager,
        EntityManagerInterface $em
    )
    {
        $this->typeManager = $typeManager;
        parent::__construct($typeDamageFromTypeManager, $languageManager, $apiManager, $em);
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:damage-relation:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:damage-relation:all to fetch all damage relation between type for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lang = $input->getArgument('lang');

        $output->writeln('');
        $output->writeln('<info>Fetching all types damage-relation...');
        $output->writeln('For language ' . $lang . '</info>');

        if ($this->checkLanguageExists($output, $lang))
        {
            $types = $this->typeManager->getAllTypeByLanguage($lang);

            //Initialise progress bar
            $progressBar = new ProgressBar($output, sizeof($types));
            $progressBar->start();

            foreach ($types as $type) {
                /** @var TypeDamageRelationType $this->manager */
                $this->manager->createDamageFromType($type, $lang);
                $progressBar->advance();
            }

            $progressBar->finish();

            return command::SUCCESS;
        }
        return command::FAILURE;
    }

}