<?php

namespace App\DataFixtures;

use App\Entity\Testimony;
use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TestimonyFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $client = $this->getReference(ClientFixture::CLIENT_REFERENCE . $i, Client::class);

            $testimonyCount = $faker->numberBetween(1, 3);

            for ($j = 0; $j < $testimonyCount; $j++) {
                $testimony = new Testimony();
                $testimony->setAuthor($client);
                $testimony->setContent($faker->paragraphs(3, true));

                $manager->persist($testimony);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixture::class,
        ];
    }
}


