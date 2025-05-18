<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Les fixtures sont chargées automatiquement par Doctrine
        // grâce aux dépendances définies dans chaque classe de fixture
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            ClientFixture::class,
            ProjetFixture::class,
            LivrableFixture::class,
            TestimonyFixture::class,
        ];
    }
}
