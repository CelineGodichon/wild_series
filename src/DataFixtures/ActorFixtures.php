<?php

namespace App\DataFixtures;

use App\Service\Slugify;
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

    /**
     * Load data fixtures with the passed EntityManager
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        foreach (self::ACTORS as $name => $data) {
            $actor = new Actor();
            $actor->setName($name);
            $actor->setSlug($slugify->generate($name));
            foreach ($data['programs'] as $program) {
                $actor->addProgram($this->getReference($program));
            }
            $manager->persist($actor);
            $this->addReference('actor_' . $name, $actor);
        }
        $faker = Faker\Factory::create('en_US');
        for ($i = 0; $i < 50; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name);
            $actor->setSlug($slugify->generate($actor->getName()));
            $number = rand(0, 2);
            $actor->addProgram($this->getReference('program_' . $number));
            $manager->persist($actor);
        }
        $manager->flush();
    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}