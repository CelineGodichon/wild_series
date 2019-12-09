<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    const PROGRAMS = [
        'Buffy The Vampire Slayer' => [
            'summary' => 'In every generation there is a chosen one... 
                she alone will stand against the vampires, the demons and the forces of 
                darkness. She is the slayer." Buffy Summers knows this tale by heart, and 
                no matter how hard she tries to be just a "normal girl", she can not escape 
                from her destiny... Thankfully, she is not alone in her quest to save the world, 
                as she has the help of her friends, the hilarious (and surprisingly quite effective) 
                evil-fighting team called "The Scooby Gang". Together, Buffy & co. will slay their 
                demons, survive one apocalypse after another, attend high school and college... and 
                above all, understand that growing up can truly be Hell sometimes... literally.',
            'poster' => 'http://via.placeholder.com/200x360',
            'category' => 'category_1'],
        'The Hauting of Hill House' => [
            'summary' => 'In the summer of 1992, Hugh and Olivia Crain and their children – Steven, 
                Shirley, Theodora, Luke, and Nell – move into Hill House to renovate the mansion in order to 
                sell it and build their own house, designed by Olivia. However, due to unexpected repairs, 
                they have to stay longer, and they begin to experience increasing paranormal phenomena that 
                results in a tragic loss and the family fleeing from the house. Twenty-six years later, the 
                Crain siblings and their estranged father reunite after tragedy strikes again, and they are 
                forced to confront how their time in Hill House had affected each of them.',
            'poster' => 'http://via.placeholder.com/200x360',
            'category' => 'category_1'],
        'Friends' => [
            'summary' => 'Rachel Green, Ross Geller, Monica Geller, Joey Tribbiani, Chandler Bing and Phoebe 
                Buffay are six 20 something year-olds, living off of one another in the heart of New York City. 
                Over the course of ten years, this average group of buddies goes through massive mayhem, family 
                trouble, past and future romances, fights, laughs, tears and surprises as they learn what it 
                really means to be a friend.',
            'poster' => 'poster',
            'category' => 'http://via.placeholder.com/200x360'
        ]
    ];

    public function load(ObjectManager $manager)
    {
        $i = 0;
        $slugify = new Slugify();
        foreach (self::PROGRAMS as $title => $data){
            $program = new Program();
            $program->setTitle($title);
            $program->setSummary($data['summary']);
            $program->setPoster($data['poster']);
            $program->setCategory($this->getReference($data['category']));
            $program->setSlug($slugify->generate($title));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
            $i++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}