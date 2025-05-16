<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProjetFixture extends Fixture implements DependentFixtureInterface
{
    public const PROJET_REFERENCE = 'projet';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $projets = [];

        for ($i = 0; $i < 10; $i++) {
            $projet = new Projet();
            $projet->setName($faker->sentence(3));
            $projet->setDescription($faker->paragraph(2));
            $projet->setClient($this->getReference(ClientFixture::CLIENT_REFERENCE . rand(1, 10), Client::class));;
            $manager->persist($projet);

            array_push($projets, $projet);
        }

        $manager->flush();

        foreach ($projets as $projet) {
            $this->addReference(self::PROJET_REFERENCE . $projet->getId(), $projet);
        }
    }

    public function getDependencies(): array
    {
        return [
            ClientFixture::class,
        ];
    }
}
