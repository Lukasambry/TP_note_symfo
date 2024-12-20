<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm',
                    'placeholder' => 'Enter user email',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Banned' => 'ROLE_BANNED',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-700',
                ],
            ])
            ->add('isVerified', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded',
                ],
                'label_attr' => [
                    'class' => 'ml-2 block text-sm text-gray-700',
                ],
            ])
            ->add('firstname', null, [
                'attr' => [
                    'class' => 'block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm',
                    'placeholder' => 'Enter first name',
                ],
            ])
            ->add('lastname', null, [
                'attr' => [
                    'class' => 'block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm',
                    'placeholder' => 'Enter last name',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
