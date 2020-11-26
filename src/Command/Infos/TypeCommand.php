<?php


namespace App\Command\Infos;


use App\Entity\Infos\Type\Type;
use App\Manager\Api\ApiManager;
use App\Manager\Infos\Type\TypeManager;
use App\Manager\Users\LanguageManager;
use App\Repository\Infos\Type\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TypeCommand extends Command
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;

    /**
     * @var TypeManager $typeManager
     */
    private TypeManager $typeManager;

    /**
     * @var ApiManager $apiManager
     */
    private ApiManager $apiManager;

    /**
     * @var LanguageManager
     */
    private LanguageManager $languageManager;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * ExcecCommand constructor
     * @param TypeManager $typeManager
     * @param ApiManager $apiManager
     */
    public function __construct(
        TypeRepository $typeRepository,
        TypeManager $typeManager,
        ApiManager $apiManager,
        LanguageManager $languageManager,
        EntityManagerInterface $em
    ) {
        $this->typeRepository = $typeRepository;
        $this->typeManager = $typeManager;
        $this->apiManager = $apiManager;
        $this->languageManager = $languageManager;
        $this->em = $em;
        parent::__construct();
    }

    /**
     * ExcecCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName('app:type:all')
            ->addArgument('lang', InputArgument::REQUIRED, 'Language used')
            ->setDescription('Execute app:pokemon to fetech all pokemon for language');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Fetch parameter
        $lang = $input->getArgument('lang');

        //Check if language exist else create language
        $language = $this->languageManager->createLanguage($lang);

        //Get list of types
        $typesList = $this->apiManager->getDetailed("https://pokeapi.co/api/v2/type")->toarray();

        //Initialise progress bar
        $progressBar = new ProgressBar($output, count($typesList['results']));

        foreach ($typesList['results'] as $type) {

            //Fetch URL details type
            $urlType = $type['url'];

            //Fetch name according the language
            $typeNameLang = $this->typeManager->getTypesInformationOnLanguage($lang, $urlType);

            //Check if the data exist in databases
            $newType = $this->typeRepository->findOneBy(['name' => $typeNameLang]);

            //If database is null, create type
            if (empty($newType) && $type['name'] !== "shadow" && $type['name'] !== "unknown") {
                dump($typeNameLang);

                $urlImg = '/images/types/' . $language->getCode() . '/';

                //Create new object and save in databases
                $newType = new Type();
                $newType->setName($typeNameLang);
                $newType->setSlug($type['name']);
                $newType->setLanguage($language);
                $newType->setImg($urlImg . $type['name'] . '.png');

                $this->em->persist($newType);
                $this->em->flush();
            }

            //Advance progressBar
            $progressBar->advance();
        }

        $progressBar->finish();

        return command::SUCCESS;
    }
}