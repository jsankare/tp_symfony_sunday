<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixture extends Fixture
{

    public const string CLIENT_REFERENCE = 'client';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $clients = [];

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setName($faker->company);
            $client->setEmail($faker->email);
            $manager->persist($client);

            array_push($clients, $client);
        }

        $manager->flush();

        foreach ($clients as $client) {
            $this->addReference(self::CLIENT_REFERENCE . $client->getId(), $client);
        }
    }
}
