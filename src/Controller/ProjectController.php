<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    #[Route('/project/{name}/{id:project}', name: 'app_project_show')]
    public function showProject(Project $project): Response
    {
        $projectUser = $project->getUsers();
        $projectStatut = $project->getStatuts();
        $taskGroup = [];
        $projectTasks = $project->getTasks();
        foreach ($projectStatut as $statut) {
            $taskGroup[$statut->getName()] = $projectTasks->filter(function (Task $task) use ($statut) {
                return $task->getStatut()->getId() === $statut->getId();
            });
        }

        return $this->render('project.html.twig', [
            'projectName' => $project->getName(),
            'project' => $project,
            'projectUser' => $projectUser,
            'taskGroup' => $taskGroup,
        ]);
    }

    #[Route(path: '/project/create', name: 'app_project_create')]
    public function createProject(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectFormType::class, $project);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($project);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
                'name' => $project->getName(),
            ]);
        }

        return $this->render('project_create.html.twig', [
            'form' => $form
        ]);

    }

    #[Route(path: '/project/remove/{name}/{id:project}', name: 'app_project_delete')]
    public function deleteProject(Project $project): Response
    {
        $project->setArchived(true);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/project/update/{name}/{id:project}', name: 'app_project_update')]
    public function updateProject(Request $request, Project $project): Response
    {
        $form = $this->createForm(ProjectFormType::class, $project, [
            'submit_label' => 'Modifier le projet',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId(),
                'name' => $project->getName()
            ]);
        }

        return $this->render('project_update.html.twig', [
            'form' => $form,
            'project' => $project
        ]);
    }

}
