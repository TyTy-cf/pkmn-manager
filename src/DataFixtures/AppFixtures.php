<?php

namespace App\DataFixtures;

use App\Entity\Game\GameVersion;
use App\Entity\Infos\Gender;
use App\Entity\Infos\Nature;
use App\Entity\Moves\DamageClass;
use App\Entity\Users\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $languageFr = new Language();
        $languageFr->setCode('fr');
        $languageFr->setTitle('Français');
        $languageFr->setImg('/public/images/language/fr.png');
        $manager->persist($languageFr);
        $languageEn = new Language();
        $languageEn->setCode('en');
        $languageEn->setTitle('English');
        $languageEn->setImg('/public/images/language/en.png');
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
        $genderAsexueFr = new Gender();
        $genderAsexueFr->setName("Asexué");
        $genderAsexueFr->setLanguage($languageFr);
        $manager->persist($genderAsexueFr);
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
        $genderAsexueEn = new Gender();
        $genderAsexueEn->setName("Asexual");
        $genderAsexueEn->setLanguage($languageEn);
        $manager->persist($genderAsexueEn);
        //GameVersion
        $gameInfoDp = new GameVersion();
        $gameInfoDp->setCode('DP');
        $gameInfoDp->setSlug('diamond-pearl');
        $gameInfoDp->setLanguage($languageFr);
        $gameInfoDp->setName('Diamant Perle');
        $manager->persist($gameInfoDp);
        $gameInfoPt = new GameVersion();
        $gameInfoPt->setCode('DP');
        $gameInfoPt->setSlug('platinum');
        $gameInfoPt->setName('Platine');
        $gameInfoPt->setLanguage($languageFr);
        $manager->persist($gameInfoPt);
        $gameInfoHgss = new GameVersion();
        $gameInfoHgss->setCode('DP');
        $gameInfoHgss->setSlug('heartgold-soulsilver');
        $gameInfoHgss->setLanguage($languageFr);
        $gameInfoHgss->setName('Or Heartgold Argent Soulsilver');
        $manager->persist($gameInfoHgss);
        $gameInfoBw = new GameVersion();
        $gameInfoBw->setCode('BW');
        $gameInfoBw->setSlug('black-white');
        $gameInfoBw->setName('Noire Blanc');
        $gameInfoBw->setLanguage($languageFr);
        $manager->persist($gameInfoBw);
        $gameInfoBw2 = new GameVersion();
        $gameInfoBw2->setCode('BW');
        $gameInfoBw2->setSlug('black-2-white-2');
        $gameInfoBw2->setName('Noire 2 Blanc 2');
        $gameInfoBw2->setLanguage($languageFr);
        $manager->persist($gameInfoBw2);
        $gameInfoXy = new GameVersion();
        $gameInfoXy->setCode('XY');
        $gameInfoXy->setSlug('x-y');
        $gameInfoXy->setLanguage($languageFr);
        $gameInfoXy->setName('X Y');
        $manager->persist($gameInfoXy);
        $gameInfoRosa = new GameVersion();
        $gameInfoRosa->setCode('XY');
        $gameInfoRosa->setSlug('omega-ruby-alpha-sapphire');
        $gameInfoRosa->setLanguage($languageFr);
        $gameInfoRosa->setName('Rubis Omega Spahir Alpha');
        $manager->persist($gameInfoRosa);
        $gameInfoSl = new GameVersion();
        $gameInfoSl->setCode('SL');
        $gameInfoSl->setSlug('sun-moon');
        $gameInfoSl->setLanguage($languageFr);
        $gameInfoSl->setName('Soleil Lune');
        $manager->persist($gameInfoSl);
        $gameInfoUsum = new GameVersion();
        $gameInfoUsum->setCode('SL');
        $gameInfoUsum->setSlug('ultra-sun-ultra-moon');
        $gameInfoUsum->setLanguage($languageFr);
        $gameInfoUsum->setName('Ultra Soleil Ultra Lune');
        $manager->persist($gameInfoUsum);
        $gameInfoSs = new GameVersion();
        $gameInfoSs->setCode('SS');
        $gameInfoSs->setSlug('sword-shield');
        $gameInfoSs->setLanguage($languageFr);
        $gameInfoSs->setName('Epée Bouclier');
        $manager->persist($gameInfoSs);
        $manager->flush();
    }
}
