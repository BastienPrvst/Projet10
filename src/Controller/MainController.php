<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(Project::class);
        $allProjects = $repository->findBy([
            'archived' => false]
        );

        return $this->render('home.html.twig',[
            'allProjects' => $allProjects]);
    }

    #[Route('/team', name: 'app_team')]
    public function team(): Response
    {
        $allUsers = $this->entityManager->getRepository(User::class)->findAll();

        return $this->render('team.html.twig', [
            'allUsers' => $allUsers,
        ]);
    }
}
