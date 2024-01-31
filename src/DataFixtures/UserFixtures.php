<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setMail('admin@gmail.fr');
        $admin->setPassword("toto");
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'passadmino')
        );
        $admin ->setPseudo('Admino');
        $admin ->setRole(['ROLE_ADMIN']);

        $manager->persist ($admin);

        $faker = Factory::create('fr_FR');

        for ($usr = 1; $usr <= 5; $usr++)
        {
            $user = new User();
            $user ->setMail($faker->email);
            $user->setRole(['ROLE_USER']);
            $user ->setPassword(
                $this->passwordHasher->hashPassword($user, 'secret')
            );
            $user->setPseudo($faker->firstName);

            $manager -> persist ($user);
        }

        $manager -> flush();

    }
}