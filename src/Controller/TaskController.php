<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskFormType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        TaskRepository $taskRepository,
        private readonly EntityManagerInterface $entityManager,
    )
    {

    }

    #[Route('/task/add/{id}', name: 'app_create_task')]
    public function addTask( Project $project, Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskFormType::class, $task, [
            'users' => $project->getUsers(),
            'statut' => $project->getStatuts(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setProject($project);
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_project_show', [
                'name' => $project->getName(),
                'id' => $project->getId(),
            ]);
        }


        return $this->render('add_task.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route(path: '/task/update/{project}/{task}', name: 'app_update_task')]
    public function updateTask( Project $project, Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskFormType::class, $task, [
            'users' => $project->getUsers(),
            'statut' => $project->getStatuts(),
            'submit' => 'Modifier'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_show', [
                'name' => $project->getName(),
                'id' => $project->getId(),
            ]);
        }

        return $this->render('update_task.html.twig', [
            'form' => $form,
            'task' => $task,
            'project' => $project,
        ]);
    }

    #[Route(path: '/task/delete/{project}/{task}', name: 'app_delete_task')]
    public function deleteTask( Project $project, Task $task): Response
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_project_show', [
            'name' => $project->getName(),
            'id' => $project->getId(),
        ]);

    }

}
