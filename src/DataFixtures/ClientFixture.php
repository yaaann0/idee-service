<?php

namespace App\DataFixtures;

use App\Entity\Client;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 100; $i++) { 
            $client = new Client();
            $client
                ->setFullname('Client '. $i)
                ->setAdress($i . ' place hôtel de ville')
                ->setPostalCode(12000 + $i)
                ->setCity('ville ' . $i)
                ->setCreatedAt(new DateTime());
            $manager->persist($client);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['examples'];
    }
}
