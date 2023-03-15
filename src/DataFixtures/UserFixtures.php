<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departments = array(
            'Cat. 1' => ['ROLE_USER_1'],
            'Cat. 2' => ['ROLE_USER_2'],
            'Administration' => ['ROLE_ADMIN']
        );

        foreach ($departments as $key => $item) {
            $department = new Department();
            $department
                ->setFullname($key)
                ->setRoles($item)
            ;
            $manager->persist($department);

            $departments[$key] = $department;
        }

        $user = new User();
        $createdAt = new \DateTime();

        $user
            ->setEmail('me@yaaann.ovh')
            ->setLastname('Tachier')
            ->setFirstname('Yann')
            ->setPassword('123456')
            ->setIsActive(true)
            ->setDepartment($departments['Administration'])
            ->setRoles($departments['Administration']->getRoles())
            ->setCreatedAt($createdAt)
        ;
        
        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init'];
    }
}
