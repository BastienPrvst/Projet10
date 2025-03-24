<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
            ])
            ->add('surname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('contract', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Alternant' => 'Alternant',
                    'Stage' => 'Stage'
                ],
                'label' => 'Statut',
                'required' => true,
            ])
            ->add('entry_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'button button-submit'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
