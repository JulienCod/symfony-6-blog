<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr'=>[
                'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'minlength'=> '2',
                'maxlength' => "50",
            ],
            'label' => ' Adresse email',
            'label_attr'=>[
                'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
            ],
            'constraints'=>[
                new Assert\NotBlank([
                    'message' => 'Veuillez renseigner votre adresse email',
                ]),
                new Assert\Length([
                    'min'=>5, 
                    'minMessage' => 'Votre email ne peut pas contenir moins de {{ limit }} caractères',
                    'max'=>180,
                    'maxMessage' => 'Votre email ne peut pas contenir plus de {{ limit }} caractères',

                ])
            ]
        ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un mot de passe',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe ne peut pas contenir moins de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => ' Prénom',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'constraints'=>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre prénom',
                    ]),
                    new Assert\Length([
                        'min'=>2, 
                        'minMessage' => 'Votre prénom ne peut pas contenir moins de {{ limit }} caractères',
                        'max'=>50,
                        'maxMessage' => 'Votre prénom ne peut pas contenir plus de {{ limit }} caractères',
                        ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => 'Nom',
                'label_attr'=>[
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],
                'constraints'=>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre nom',
                    ]),
                    new Assert\Length([
                        'min'=>2, 
                        'minMessage' => 'Votre nom ne peut pas contenir moins de {{ limit }} caractères',
                        'max'=>50,
                        'maxMessage' => 'Votre nom ne peut pas contenir plus de {{ limit }} caractères',

                    ])
                ]
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'label' => 'En m\'inscrivant à ce site j\'accepte que mes données soit enregistré',
                'label_attr'=>[
                    'class' => "ml-2 text-sm  mb-2 font-medium text-gray-900 dark:text-gray-300"
                ],
                'attr' => [
                    'class' => "w-4 h-4 border mb-2 border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-600 dark:border-gray-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veillez accepter que vos données soit enregistré en base de données',
                    ])
                ]
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
