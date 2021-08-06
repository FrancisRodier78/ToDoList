<?php

namespace App\Command;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class AnonymeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    // La commande ne marche qu'en environnement de dev
    protected static $defaultName = 'app:anonyme';

    //protected EntityManagerInterface $manager;
    private EntityManagerInterface $manager;

    //protected function __construct(EntityManagerInterface $manager) 
    public function __construct(EntityManagerInterface $manager) 
    {
        parent::__construct();
        $this->manager = $manager;    
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Rattache les tâches anonymes à l\'utilisateur Anonymous.')
            ->setHelp('Cette commande permet de rattacher des tâches sans utilisateurs à un utilisateur anonymous.')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo1 = $this->manager->getRepository(User::class);
        $user = $repo1->findOneBy(['username' => 'anonymous']);

        $repo2 = $this->manager->getRepository(Task::class);
        $tasks = $repo2->findBy(['author' => null]);

        $nbTasks = count($tasks);
        for ($i=0; $i < $nbTasks; $i++) { 
            $tasks[$i]->setAuthor($user);
            $this->manager->persist($tasks[$i]);
        }

        $this->manager->flush();

        return 0;
    }
}