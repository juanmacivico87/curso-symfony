<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="app_categories_get")
     */
    public function getCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categories/new", name="app_category_new")
     */
    public function createNewCategory(CategoryRepository $categoryRepository, EntityManagerInterface $entityManagerInterface, Request $request)
    {
        $category = new Category();

        if (!$this->isCsrfTokenValid('category', $request->request->get('categoryToken'))) {
            return $this->render('category/new.html.twig', [
                'category' => $category,
            ]);
        }

        if (!$categoryName = $request->request->get('categoryName', null)) {
            // Display a error message
            $this->addFlash( 'danger', 'Please, enter a name to the new category' );
            return $this->render('category/new.html.twig', [
                'category' => $category,
            ]);
        }
        // Set name of the new category
        $category->setName($categoryName);
        // Save new category
        $entityManagerInterface->persist($category);
        $entityManagerInterface->flush();
        // Display a success message
        $this->addFlash( 'success', 'The new category has been saved' );
        // Redirect to list of categories
        return $this->redirectToRoute('app_categories_get');
    }
}
