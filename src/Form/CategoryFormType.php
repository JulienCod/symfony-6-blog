<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "100",
                ],
                'label' => 'Titre',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'constraints'=>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner le titre',
                    ]),
                    new Assert\Length([
                        'min'=>2,
                        'minMessage' => 'Le titre ne peut pas contenir moins de {{ limit }} caractères',
                        'max'=>100,
                        'maxMessage' => 'Le titre ne peut pas contenir plus de {{ limit }} caractères',
                    ])
                ]
            ])
            ->add('categoryOrder', NumberType::class, [
                'label' => 'Category Order',
                'attr' => [
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                ],
                'html5' => true, // Activer les fonctionnalités HTML5 pour le champ number
                'input' => 'number', // Utiliser un champ de saisie de nombre avec flèches d'incrémentation
                'constraints' => [
                    new Assert\Range([
                        'min' => 0,
                        'minMessage' => 'Le nombre doit être supérieur ou égal à zéro.',
                    ]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'label' => 'Categories',
                'multiple' => false,
                'required' => false, // Champ non obligatoire
                'empty_data' => null, // Valeur par défaut vide
                'query_builder' => function(CategoryRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
