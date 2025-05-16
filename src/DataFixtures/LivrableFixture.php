<?php

namespace App\DataFixtures;

use App\Entity\Livrable;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LivrableFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $livrable = new Livrable();
            $livrable->setName($faker->sentence(3));
            $livrable->setGoal($faker->paragraph(2));
            $livrable->setGoalDate($faker->dateTimeBetween('+1 year', '+3 year'));
            $livrable->setStartDate($faker->dateTimeBetween('-2 year', '-2 month'));
            $livrable->setProjet($this->getReference(ProjetFixture::PROJET_REFERENCE . rand(1, 10), Projet::class));
            $manager->persist($livrable);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjetFixture::class,
        ];
    }
}
