<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/user/{id}', name: 'app_user')]
    #[IsGranted("ROLE_ADMIN")]
    public function showUser(Request $request, User $user): Response
    {

        $form = $this->createForm(ChangeUserFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_team');
        }


        return $this->render('user.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/user/{id}/delete', name: 'app_user_delete')]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteUser(User $user):Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_team');
    }

}
