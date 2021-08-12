<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks/todo", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', [
            'author' => $this->getUser(),
            'tasksToDo' => $this->getDoctrine()->getRepository('App:Task')->findBy([
                'isDone' => '0', 
                'author' => $this->getUser()]), 
            'isDoneToDo' => '0',
            'tasksFinish' => $this->getDoctrine()->getRepository('App:Task')->findBy([
                'isDone' => '1', 
                'author' => $this->getUser()]), 
            'isDoneFinish' => '1',
            'tasksAnonyme' => $this->getDoctrine()->getRepository('App:Task')->findBy([
                'author' => $this->getDoctrine()->getRepository('App:User')->findBy(['username' => 'Anonymous']),
            ]), 
        ]);
    } 

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $task->setAuthor($this->getUser());

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->setIsDone(!$task->getIsDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été modifiée.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        if ($this->getUser() !== $task->getAuthor()) {
            if ($task->getAuthor()->getId() === -1) {
                if(!$this->isGranted('ROLE_ADMIN')) {
                    $this->addFlash('error', 'Seul un admin peut supprimer une tâche de l\'utilisateur anonyme !');
                    return $this->redirectToRoute('task_list');
                }
            }

            if(!$this->isGranted('ROLE_ADMIN')) {
                $this->addFlash('error', 'Seul l\'auteur de la tâche ou un admin peut la supprimer !');
                return $this->redirectToRoute('task_list');
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
