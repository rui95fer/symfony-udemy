<?php

namespace App\DataFixtures;

use App\Entity\MicroPost;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Portugal!');
        $microPost1->setText('Portugal is a beautiful country located in southwestern Europe, known for its rich history, stunning beaches, and vibrant culture.');
        $microPost1->setCreated(new DateTimeImmutable());
        $microPost1->setUser($user);

        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welcome to France!');
        $microPost2->setText('France is a country known for its rich history, art, fashion, and cuisine. The Eiffel Tower, located in Paris, is one of the most iconic landmarks in the world.');
        $microPost2->setCreated(new DateTimeImmutable());
        $microPost2->setUser($user);


        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Spain!');
        $microPost3->setText('Spain is a country known for its rich culture, beautiful beaches, and vibrant cities. From the artistic treasures of Madrid to the architectural wonders of Barcelona, Spain has something to offer for every kind of traveler.');
        $microPost3->setCreated(new DateTimeImmutable());
        $microPost3->setUser($user);

        $manager->persist($microPost3);

        $manager->flush();
    }
}
