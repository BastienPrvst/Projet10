<?php

namespace App\Form;

use App\Entity\Statut;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('deadline', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => false,
            ])
            ->add('assigned_to', EntityType::class, [
                'label' => 'Membre',
                'class' => User::class,
                'choices' => $options['users'],
                'required' => false,
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                },
            ])
            ->add('statut', EntityType::class, [
                'class' => Statut::class,
                'choices' => $options['statut'],
                'choice_label' => function (Statut $statut) {
                    return $statut->getName();
                },
                'multiple' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'button button-submit',
                ],
                'label' => $options['submit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'users' => [],
            'statut' => [],
            'submit' => 'Ajouter',
        ]);
    }
}
