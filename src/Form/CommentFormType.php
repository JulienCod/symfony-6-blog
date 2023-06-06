<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CommentFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $author = $user ? $user->getFirstName() . ' ' . $user->getLastName() : '';
        $builder
        ->add('content', TextType::class, [
            'attr'=>[
                'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'minlength'=> '2',
            ],
            'label' => 'Contenu',
            'label_attr'=>[
                'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            ],
            'constraints'=>[
                new Assert\NotBlank([
                    'message' => 'Veuillez renseigner un commentaire',
                ]),
                new Assert\Length([
                    'min'=>2, 
                    'minMessage' => 'Le contenu de l\'article ne peut pas contenir moins de {{ limit }} caractères',
                    ])
            ]
        ])
        ->add('author', TextType::class, [
            'data' => $author, // Pré-remplit le champ avec la valeur de $author
            'attr'=>[
                'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'minlength'=> '2',
                'maxlength' => "50",
            ],
            'label' => 'Auteur',
            'label_attr'=>[
                'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
            ],
            'constraints'=>[
                new Assert\NotBlank([
                    'message' => 'Veuillez renseigner votre nom et prénom',
                ]),
                new Assert\Length([
                    'min'=>2, 
                    'minMessage' => 'Le champ ne peut pas contenir moins de {{ limit }} caractères',
                    'max'=>50,
                    'maxMessage' => 'Le champ ne peut pas contenir plus de {{ limit }} caractères',
                    ])
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
