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
        $admin->setEmail('admin@gmail.fr');
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'passadmino')
        );
        $admin ->setName('Admino');
        $admin ->setRoles(['ROLE_ADMIN']);

        $manager->persist ($admin);

        $faker = Factory::create('fr_FR');

        for ($usr = 1; $usr <= 5; $usr++)
        {
            $user = new User();
            $user ->setEmail($faker->email);
            $user->setRoles(['ROLE_USER']);
            $user ->setPassword(
                $this->passwordHasher->hashPassword($user, 'secret')
            );
            $user->setName($faker->firstName);

            $manager -> persist ($user);
        }

        $manager -> flush();

    }
}