<?php

namespace App\DataFixtures;

use App\Entity\SheetState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SheetStateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departments = array(
            'draft' => 'A saisir',
            'validated' => 'Validée',
            'signed' => 'Signée'
        );

        foreach ($departments as $key => $item) {
            $department = new SheetState();
            $department
                ->setSlug($key)
                ->setLabel($item)
            ;
            $manager->persist($department);

            $departments[$key] = $department;
        }

        $manager->flush();
    }
}
