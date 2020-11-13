<?php

namespace App\DataFixtures;

use App\Entity\Infos\Abilities;
use App\Entity\Infos\Type;
use App\Entity\Pokemon\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $talentIntimidation = new Abilities();
        $talentIntimidation->setNom("Intimidation");
        $talentIntimidation->setDescription("Réduit d'un niveau l'attaque du pokemon adverse lorsque mis en combat");
        $manager->persist($talentIntimidation);
        $talentImpudence = new Abilities();
        $talentImpudence->setNom("Impudence");
        $talentImpudence->setDescription("Augmente l'attaque du pokemon d'un niveau lorsqu'il met un KO");
        $manager->persist($talentImpudence);

        $typeDragon = new Type();
        $typeDragon->setNom("Dragon");
        $manager->persist($typeDragon);
        $typeVol = new Type();
        $typeVol->setNom("Vol");
        $manager->persist($typeVol);

        $drattak = new Pokemon();
        $drattak->setNom("Drattak");
        $drattak->setBasePv(95);
        $drattak->setBaseAtk(135);
        $drattak->setBaseDef(80);
        $drattak->setBaseSpa(110);
        $drattak->setBaseSpd(80);
        $drattak->setBaseSpe(100);
        $drattak->addTalent($talentIntimidation);
        $drattak->addTalent($talentImpudence);
        $drattak->addType($typeDragon);
        $drattak->addType($typeVol);
        $manager->persist($drattak);

        $manager->flush();
    }
}
