<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

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
