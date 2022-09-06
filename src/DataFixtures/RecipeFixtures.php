<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Recipe;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();

        foreach ($users as $user){
            $ingredients[] = [];
            for($index = 0; $index<5; $index++){
                $ingredients[] = [$faker->numberBetween(1, 500) . $faker->word];
            }

            $recipe = new Recipe();
            $recipe->setTitle($faker->title);
            $recipe->setContent($faker->text);
            $recipe->setIngredients($ingredients);
            $recipe->setPreparationTime($faker->numberBetween(5,60));
            $recipe->setCookingTime($faker->numberBetween(15,120));
            $recipe->setDifficulty($faker->numberBetween(1,5));
            $recipe->setUser($user);


            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return[
            UserFixtures::class
        ];
    }
}