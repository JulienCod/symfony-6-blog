<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => ' Adresse email',
                'label_attr'=>[
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2, 'max'=>180])
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => ' Prénom',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2, 'max'=>50])
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => 'Nom',
                'label_attr'=>[
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2, 'max'=>50])
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'first_options' => ['label' => 'Mot de passe',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'attr'=>[
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ]],
                'second_options' => ['label' => 'Confirmer le mot de passe',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'attr'=>[
                    'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ]],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => "w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    ]
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
