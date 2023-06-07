<?php 

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    #[Route('/', name:'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([],['categoryOrder' => 'asc']);
        return $this->render('admin/category/index.html.twig',compact('categories'));
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('/ajout', name:'add')]
    public function addCategory(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
    ): Response
    {
        // création d'une catégorie
        $category = new Category();

        //création du formulaire
        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        //traitement de la requête du formulaire
        $categoryForm->handleRequest($request);

        //on vérifie si le formulaire et soumis et valide
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            // on génère le slug
            $slug = $slugger->slug($category->getName());
            $category->setslug($slug);

            // on enregistre en base de donnée
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La categorie a été créé avec succès');

            //redirection
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/add.html.twig',[
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('/edition/{id}', name: 'edit')]
    public function edit(
        Category $category,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
    ): Response
    {
        //création du formulaire
        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        //traitement de la requête du formulaire
        $categoryForm->handleRequest($request);

        //on vérifie si le formulaire et soumis et valide
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            // on génère le slug
            $slug = $slugger->slug($category->getName());
            $category->setslug($slug);

            // on enregistre en base de donnée
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'La categorie a été modifié avec succès');

            //redirection
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig',[
            'categoryForm' => $categoryForm->createView(),
            'category' => $category,
        ]);
    }

    /**
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/delete/{id}', name:'delete')]
public function delete(
    Category $category,
        EntityManagerInterface $entityManager
    ): Response
    {
        if($category){

            $entityManager->remove($category);
            $entityManager->flush();

            $this->addFlash('success', 'La categorie a été supprimé avec succès');

            //redirection
            return $this->redirectToRoute('admin_category_index');
        }
        $this->addFlash('alert', 'La categorie n\'a pas été trouvé et n\'a donc pas était supprimé');

        //redirection
        return $this->redirectToRoute('admin_category_index');
    }
}