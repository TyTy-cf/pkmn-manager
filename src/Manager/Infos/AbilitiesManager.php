<?php


namespace App\Manager\Infos;


use App\Entity\Infos\Abilities;
use App\Entity\Pokemon\Pokemon;
use App\Manager\Api\ApiManager;
use App\Repository\Infos\AbilitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AbilitiesManager
{
    /**
     * @var AbilitiesRepository
     */
    private $abilitiesRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ApiManager
     */
    private $apiManager;

    /**
     * PokemonManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ApiManager $apiManager
     */
    public function __construct(EntityManagerInterface $entityManager, ApiManager $apiManager)
    {
        $this->entityManager = $entityManager;
        $this->apiManager = $apiManager;
        $this->abilitiesRepository = $this->entityManager->getRepository(Abilities::class);
    }

    /**
     * @param object $language
     * @param string $lang
     * @param $abilities
     * @param Pokemon $pokemon
     * @throws TransportExceptionInterface
     */
    public function saveNewAbilities($language, string $lang, $abilities, Pokemon $pokemon)
    {
        // Iterate the types from the json, create the type if not existing or get it
        foreach ($abilities as $ability) {
            $urlAbility = $ability['ability']['url'];
            $abilitiesDetailed = $this->getAbilitiesInformationsOnLanguage($lang, $urlAbility);

//            $abilityName = $ability['ability']['name'];

            if (($newAbility = $this->abilitiesRepository->findOneBy(['name' => $abilitiesDetailed['name']])) == null) {

                $newAbility = new Abilities();
//                $newAbility->setNameEn(ucfirst($abilityName));
                $newAbility->setName(ucfirst($abilitiesDetailed['name']));
                $newAbility->setDescription($abilitiesDetailed['description']);
                $newAbility->setLanguage($language);

                $this->entityManager->persist($newAbility);
            }

            $pokemon->addAbilities($newAbility);
            $this->entityManager->flush();
        }
    }

    /**
     * @param $lang
     * @param $url
     * @return array
     * @throws TransportExceptionInterface
     */
    public function getAbilitiesInformationsOnLanguage($lang, $url)
    {
        $apiResponse = $this->apiManager->getDetailed($url)->toArray();
        $descriptionAbility = null;
        $nameAbility = null;

        foreach ($apiResponse['names'] as $name) {
            if ($name['language']['name'] === $lang) {
                $nameAbility = $name['name'];
            }
        }

        foreach ($apiResponse['flavor_text_entries'] as $flavor_text_entry) {
            if ($flavor_text_entry['language']['name'] === $lang) {
                $descriptionAbility = $flavor_text_entry['flavor_text'];
            }
        }

        return $AbilitiesInformation = [
            'name' => $nameAbility,
            'description' => $descriptionAbility,
        ];

    }
}