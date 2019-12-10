<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        // Création d’un utilisateur de type “auteur”
        $celine = new User();
        $celine->setEmail('celine@gmail.fr');
        $celine->setRoles(['ROLE_SUBSCRIBER']);
        $celine->setPassword($this->passwordEncoder->encodePassword(
            $celine,
            'celine'
        ));

        $manager->persist($celine);

        $subscriberauthor = new User();
        $subscriberauthor->setEmail('subscriber@monsite.com');
        $subscriberauthor->setRoles(['ROLE_SUBSCRIBER']);
        $subscriberauthor->setPassword($this->passwordEncoder->encodePassword(
            $subscriberauthor,
            'subscriberpassword'
        ));

        $manager->persist($subscriberauthor);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
