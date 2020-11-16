<?php

namespace App\DataFixtures;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Gender;
use App\Entity\Infos\Nature;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /* Gender */
        $genderMale = new Gender();
        $genderMale->setNameFr("Mâle");
        $genderMale->setNameEn('Male');
        $manager->persist($genderMale);
        $genderFemale = new Gender();
        $genderFemale->setNameFr("Femelle");
        $genderFemale->setNameEn('Female');
        $manager->persist($genderFemale);
        $genderAsexue = new Gender();
        $genderAsexue->setNameFr("Asexué");
        $genderAsexue->setNameEn('Asexual');
        $manager->persist($genderAsexue);
        /* Nature */
        $assure = new Nature();
        $assure->setNameFr("Assuré");
        $assure->setNameEn("Bold");
        $assure->setStatsBonus("+def");
        $assure->setStatsPenalty("-atk");
        $manager->persist($assure);
        $bizarre = new Nature();
        $bizarre->setNameFr("Bizarre");
        $bizarre->setNameEn("Quirky");
        $manager->persist($bizarre);
        $brave = new Nature();
        $brave->setNameFr("Brave");
        $brave->setNameEn("Brave");
        $assure->setStatsBonus("+atk");
        $assure->setStatsPenalty("-spe");
        $manager->persist($brave);
        $calme = new Nature();
        $calme->setNameFr("Calme");
        $calme->setNameEn("Calm");
        $calme->setStatsBonus("+spd");
        $calme->setStatsPenalty("-atk");
        $manager->persist($calme);
        $discret = new Nature();
        $discret->setNameFr("Discret");
        $discret->setNameEn("Quiet");
        $discret->setStatsBonus("+spa");
        $discret->setStatsPenalty("-spe");
        $manager->persist($discret);
        $docile = new Nature();
        $docile->setNameFr("Docile");
        $docile->setNameEn("Docile");
        $manager->persist($docile);
        $doux = new Nature();
        $doux->setNameFr("Doux");
        $doux->setNameEn("Mild");
        $doux->setStatsBonus("+spa");
        $doux->setStatsPenalty("-def");
        $manager->persist($doux);
        $foufou = new Nature();
        $foufou->setNameFr("Foufou");
        $foufou->setNameEn("Rash");
        $foufou->setStatsBonus("+spa");
        $foufou->setStatsPenalty("-spd");
        $manager->persist($foufou);
        $gentil = new Nature();
        $gentil->setNameFr("Gentil");
        $gentil->setNameEn("Gentle");
        $gentil->setStatsBonus("+spd");
        $gentil->setStatsPenalty("-def");
        $manager->persist($gentil);
        $hardi = new Nature();
        $hardi->setNameFr("Hardi");
        $hardi->setNameEn("Hardy");
        $manager->persist($hardi);
        $jovial = new Nature();
        $jovial->setNameFr("Jovial");
        $jovial->setNameEn("Jolly");
        $jovial->setStatsBonus("+spe");
        $jovial->setStatsPenalty("-spa");
        $manager->persist($jovial);
        $lache = new Nature();
        $lache->setNameFr("Lâche");
        $lache->setNameEn("Lax");
        $lache->setStatsBonus("+def");
        $lache->setStatsPenalty("-spd");
        $manager->persist($lache);
        $malin = new Nature();
        $malin->setNameFr("Malin");
        $malin->setNameEn("Impish");
        $malin->setStatsBonus("+def");
        $malin->setStatsPenalty("-spa");
        $manager->persist($malin);
        $malpoli = new Nature();
        $malpoli->setNameFr("Malpoli");
        $malpoli->setNameEn("Sassy");
        $malpoli->setStatsBonus("+spd");
        $malpoli->setStatsPenalty("-spe");
        $manager->persist($malpoli);
        $mauvais = new Nature();
        $mauvais->setNameFr("Mauvais");
        $mauvais->setNameEn("Naughty");
        $mauvais->setStatsBonus("+atk");
        $mauvais->setStatsPenalty("-spd");
        $manager->persist($mauvais);
        $modeste = new Nature();
        $modeste->setNameFr("Modeste");
        $modeste->setNameEn("Modest");
        $modeste->setStatsBonus("+spa");
        $modeste->setStatsPenalty("-atk");
        $manager->persist($modeste);
        $naif = new Nature();
        $naif->setNameFr("Naif");
        $naif->setNameEn("Naive");
        $naif->setStatsBonus("+spe");
        $naif->setStatsPenalty("-spd");
        $manager->persist($naif);
        $presse = new Nature();
        $presse->setNameFr("Pressé");
        $presse->setNameEn("Hasty");
        $presse->setStatsBonus("+spe");
        $presse->setStatsPenalty("-def");
        $manager->persist($presse);
        $prudent = new Nature();
        $prudent->setNameFr("Prudent");
        $prudent->setNameEn("Careful");
        $prudent->setStatsBonus("+spd");
        $prudent->setStatsPenalty("-spa");
        $manager->persist($prudent);
        $pudique = new Nature();
        $pudique->setNameFr("Pudique");
        $pudique->setNameEn("Bashful");
        $manager->persist($pudique);
        $relax = new Nature();
        $relax->setNameFr("Relax");
        $relax->setNameEn("Relaxed");
        $relax->setStatsBonus("+def");
        $relax->setStatsPenalty("-spe");
        $manager->persist($relax);
        $rigide = new Nature();
        $rigide->setNameFr("Rigide");
        $rigide->setNameEn("Adamant");
        $rigide->setStatsBonus("+atk");
        $rigide->setStatsPenalty("-spa");
        $manager->persist($rigide);
        $serieux = new Nature();
        $serieux->setNameFr("Serieux");
        $serieux->setNameEn("Serious");
        $manager->persist($serieux);
        $solo = new Nature();
        $solo->setNameFr("Solo");
        $solo->setNameEn("Lonely");
        $solo->setStatsBonus("+atk");
        $solo->setStatsPenalty("-def");
        $manager->persist($solo);
        $timide = new Nature();
        $timide->setNameFr("Timide");
        $timide->setNameEn("Timid");
        $timide->setStatsBonus("+spe");
        $timide->setStatsPenalty("-atk");
        $manager->persist($timide);
        $manager->flush();
    }
}
