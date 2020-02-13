<?php

namespace App\DataFixtures;
use Faker;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 5; $i++) {
            $number = rand(0, 2);
            $season = new Season();
            $season->setYear($faker->year);
            $season->setDescription($faker->text);
            $this->addReference('season_' . $i, $season);
            $season->setProgram($this->getReference('program_' . $number));
            $manager->persist($season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}