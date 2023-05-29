<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ResetPasswordRequestFormType extends AbstractType
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
                new Assert\Email([
                    'message' => 'veillez saisir une adresse email valide',
                ]),
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
