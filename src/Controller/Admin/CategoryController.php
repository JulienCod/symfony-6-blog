<?php 

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/categories', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name:'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([],['categoryOrder' => 'asc']);
        return $this->render('admin/category/index.html.twig',compact('categories'));
    }
}