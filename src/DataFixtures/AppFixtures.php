<?php

namespace App\DataFixtures;

use App\Entity\Versions\VersionGroup;
use App\Entity\Infos\Gender;
use App\Entity\Users\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $languageFr = new Language();
        $languageFr->setCode('fr');
        $languageFr->setName('Français');
        $languageFr->setImage('/public/images/language/fr.png');
        $manager->persist($languageFr);
        $languageEn = new Language();
        $languageEn->setCode('en');
        $languageEn->setName('English');
        $languageEn->setImage('/public/images/language/en.png');
        $manager->persist($languageEn);
        /* Gender */
        // FR
        $genderMaleFr = new Gender();
        $genderMaleFr->setName("Mâle");
        $genderMaleFr->setLanguage($languageFr);
        $genderMaleFr->setImage('/public/images/infos/gender/male.png');
        $manager->persist($genderMaleFr);
        $genderFemaleFr = new Gender();
        $genderFemaleFr->setName("Femelle");
        $genderFemaleFr->setLanguage($languageFr);
        $genderFemaleFr->setImage('/public/images/infos/gender/female.png');
        $manager->persist($genderFemaleFr);
        $genderlessFr = new Gender();
        $genderlessFr->setName("Non genré");
        $genderlessFr->setLanguage($languageFr);
        $manager->persist($genderlessFr);
        // EN
        $genderMaleEn = new Gender();
        $genderMaleEn->setName("Male");
        $genderMaleEn->setLanguage($languageEn);
        $genderMaleEn->setImage('/public/images/infos/gender/male.png');
        $manager->persist($genderMaleEn);
        $genderFemaleEn = new Gender();
        $genderFemaleEn->setName("Female");
        $genderFemaleEn->setLanguage($languageEn);
        $genderFemaleEn->setImage('/public/images/infos/gender/female.png');
        $manager->persist($genderFemaleEn);
        $genderlessEn = new Gender();
        $genderlessEn->setName("Genderless");
        $genderlessEn->setLanguage($languageEn);
        $manager->persist($genderlessEn);
        $manager->flush();
    }
}
