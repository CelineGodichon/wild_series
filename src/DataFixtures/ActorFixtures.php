<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface{

    const ACTORS = [
        'Jennifer Aniston' =>[
            'programs' => ['program_2', 'program_1']
        ],
        'Sarah Michelle Gellar'=>[
            'programs' => ['program_0']
        ],
        'Carla Gugino' =>[
            'programs' => ['program_1']
        ],
        'Mattew Perry' =>[
            'programs' => ['program_2']
        ],
        'David Boreanaz' =>[
            'programs' => ['program_0']
        ],
        'James Marsters' =>[
            'programs' => ['program_0']
        ],
        'Elizabeth Reaser' =>[
            'programs' => ['program_1']
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $actorName => $data){
            $actor = new Actor();
            $actor->setName($actorName);
            foreach ($data['programs'] as $program) {
                $actor->addProgram($this->getReference($program));
            }
            $manager->persist($actor);
        }
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 50; $i++) {
            $number = rand(0, 2);
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->addProgram($this->getReference('program_' . $number));
            $manager->persist($actor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

}