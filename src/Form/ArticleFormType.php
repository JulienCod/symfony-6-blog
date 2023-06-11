<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
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
            ->add('Content', TextareaType::class, [
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '50',
                ],
                'label' => 'Contenu',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'constraints'=>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner un contenu pour l\'article',
                    ]),
                    new Assert\Length([
                        'min'=>5, 
                        'minMessage' => 'Le contenu de l\'article ne peut pas contenir moins de {{ limit }} caractères',
                        ])
                ]
            ])
            ->add('Author', TextType::class, [
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
                        'message' => 'Veuillez renseigner votre l\'auteur',
                    ]),
                    new Assert\Length([
                        'min'=>2, 
                        'minMessage' => 'L\'auteur ne peut pas contenir moins de {{ limit }} caractères',
                        'max'=>50,
                        'maxMessage' => 'L\'auteur ne peut pas contenir plus de {{ limit }} caractères',
                        ])
                ]
            ])
            ->add('image', FileType::class,[
                'label' => 'Images',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'label_attr' => [
                    'class' => 'text-gray-900 dark:text-gray-300'
                ]
                // 'constraints' => [
                //     new Image([
                //         'maxWidth' => 1280,
                //         'maxWidthMassage' => 'L\'image doit faire 1280 pixels de large maximum'
                //     ])
                // ]
            ])
            ->add('status',ChoiceType::class, [
                'choices'=> [
                    'Actif' => 'Actif',
                    'Brouillon' => 'Brouillon',
                    'Archive' => 'Archive',
                    'Inactif' => 'Inactif',
                ],
                'attr'=>[
                    'class' => 'bg-gray-50 border mb-2 border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                    'minlength'=> '2',
                    'maxlength' => "50",
                ],
                'label' => 'Statut',
                'label_attr'=>[
                    'class' => "block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                ],
                'constraints'=>[
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre statut',
                    ]),
                    new Assert\Length([
                        'min'=>2, 
                        'minMessage' => 'Votre statut ne peut pas contenir moins de {{ limit }} caractères',
                        'max'=>50,
                        'maxMessage' => 'Votre statut ne peut pas contenir plus de {{ limit }} caractères',
                        ])
                ]
            ])
            ->add('category', EntityType::class, [
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
                'multiple' => true, // Si vous souhaitez autoriser la sélection multiple
                'expanded' => false, // Si vous souhaitez afficher les catégories sous forme de cases à cocher au lieu d'une liste déroulante
                'group_by' => 'parent.name', // on groupe les catégories avec leur parent
                'query_builder' => function(CategoryRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL') //selection des catégories ou les parents ne sont pas null
                        ->orderBy('c.name', 'ASC'); // tri 
                }
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
