<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SectorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $sectors = array(
            'PM' => 'PM',
            'LI' => 'LI',
            'EV' => 'EV'
        );

        foreach ($sectors as $key => $item) {
            $sector = new Sector();
            $sector
                ->setShortname($key)
                ->setFullname($item)
            ;
            $manager->persist($sector);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init'];
    }
}
