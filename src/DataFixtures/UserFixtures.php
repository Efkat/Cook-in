<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        
        for($index = 0; $index < 10; $index++){
            $name = $faker->name;
            $user = new User();
            $user->setUsername($name);
            $user->setPassword($name . $index);
            if($index === 0){
                $user->setRoles(["ROLE_ADMIN"]);
            }
            $manager->persist($user);
        }
        $manager->flush();
    }
}
