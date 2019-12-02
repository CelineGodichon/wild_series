<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 20; $i++) {
            $number = rand(0, 3);
            $episode = new Episode();
            $episode->setTitle($faker->text);
            $episode->setNumber($faker->numberBetween(1, 6));
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . $number));
            $manager->persist($episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}