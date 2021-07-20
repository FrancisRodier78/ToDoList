<?php

namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
  
class AnonymousCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:anonymous';
  
    protected EntityManagerInterface $manager;

    protected function __construct(EntityManagerInterface $manager) 
    {
        $this->manager = $manager;    
    }

    protected function configure()
    {
        $this
            ->setDescription('Rattache les tâches anonymes à l\'utilisateur Anonymous.')
            ->setHelp('Cette commande permet de rattacher des tâches sans utilisateurs à un utilisateur anonyme.');
    }
  
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        //$this->manager 
        //$em = $this->getDoctrine()->getManager();

        //, UserRepository $repo1, TaskRepository $repo2
        $repo1 = $this->manager->getRepository(User::class);
        $user = $repo1->findOneBy(['username' => 'anonymous']);

        $repo2 = $this->manager->getRepository(Task::class);
        $tasks = $repo2->findall(['author' => null]);

        $nbTasks = count($tasks);
        for ($i=0; $i < $nbTasks; $i++) { 
            $tasks[$i]->setAuthor($user);
            $this->manager->persist($tasks[$i]);
        }

        $this->manager->flush();

        return 0;
    }
}