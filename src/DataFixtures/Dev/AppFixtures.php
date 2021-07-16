<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Gestion des utilisateurs
        $users = [];

        //    Gestion de l'utilisateur admin
        $user = new User();
        $hash = $this->encoder->encodePassword($user, 'password');
        $admin = 'ToDoAdmin';
        $roles = [];
        $roles[] = 'ROLE_ADMIN';

        $user->setUsername($admin)
             ->setPassword($hash)
             ->setEmail($faker->email)
             ->setRoles($roles);
    
        $manager->persist($user);
        $users[] = $user;

        //    Gestion de l'utilisateur Anonymous
        $user = new User();
        $hash = $this->encoder->encodePassword($user, 'password');
        $userAnonymous = 'Anonymous';
        $roles = [];
        $roles[] = 'ROLE_USER';

        $user->setId(-1)
             ->setUsername($userAnonymous)
             ->setPassword($hash)
             ->setEmail($faker->email)
             ->setRoles($roles);
    
        $manager->persist($user);

        //    Gestion des utilisateurs user
        for ($i=0; $i < 3; $i++) { 
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');
            $roles = [];
            $roles[] = 'ROLE_USER';

            $user->setUsername($faker->lastname)
                 ->setPassword($hash)
                 ->setEmail($faker->email)
                 ->setRoles($roles);
        
            $manager->persist($user);
            $users[] = $user;
        }

        // Gestion des tâches attribuées
        for ($i=0; $i < 10; $i++) { 
            $task = new Task;
            $user = $users[mt_rand(0, count($users) - 1)];

            $title = $faker->sentence(3);
            $content = $faker->sentence(7);

            $task->setTitle($title)
                 ->setContent($content)
                 ->setAuthor($user);

            $manager->persist($task);
        }

        // Gestion des tâches anonymes
        for ($i=0; $i < 4; $i++) { 
            $task = new Task;
            $user = $users[1];

            $title = $faker->sentence(3);
            $content = $faker->sentence(7);

            $task->setTitle($title)
                 ->setContent($content)
                 ->setAuthor($user);

            $manager->persist($task);
        }

        $manager->flush();
    }
}
