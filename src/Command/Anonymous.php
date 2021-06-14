<?php

namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  
//class AnonymousCommand extends Command, Controller
class AnonymousCommand extends Command
//, AbstractController
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:Anonymous';
  
    protected function configure()
    {
        $this
            ->setDescription('Rattache les tâches anonymes à l\'utilisateur Anonymous.')
            ->setHelp('Cette commande permet de rattacher des tâches sans utilisateurs à un utilisateur anonyme.');
    }
  
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getDoctrine()->getManager();

        //, UserRepository $repo1, TaskRepository $repo2
        $repo1 = $this->getDoctrine()->getRepository(User::class);
        $user = $repo1->findOneBy(['username' => 'Anonymous']);

        $repo2 = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $repo2->findall(['author' => null]);

        $nbTasks = count($tasks);
        for ($i=0; $i < $nbTasks; $i++) { 
            $tasks[$i]->setAuthor($user);
            $em->persist($tasks[$i]);
        }

        $em->flush();
    }
}