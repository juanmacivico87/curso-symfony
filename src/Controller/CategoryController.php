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
    public function createNewCategory(EntityManagerInterface $entityManager, Request $request)
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

        if (!$categoryColour = $request->request->get('categoryColour', null)) {
            // Display a error message
            $this->addFlash( 'danger', 'Please, enter a colour to the new category' );
            return $this->render('category/new.html.twig', [
                'category' => $category,
            ]);
        }

        // Set fields of the new category
        $category->setName($categoryName);
        $category->setColour($categoryColour);
        // Save new category
        $entityManager->persist($category);
        $entityManager->flush();
        // Display a success message
        $this->addFlash( 'success', 'The new category has been saved' );
        // Redirect to list of categories
        return $this->redirectToRoute('app_categories_get');
    }

    /**
     * @Route("/categories/edit/{id}", name="app_category_edit")
     */
    public function editCategory(Category $category, EntityManagerInterface $entityManager, Request $request)
    {
        if (!$this->isCsrfTokenValid('category', $request->request->get('categoryToken'))) {
            return $this->render('category/edit.html.twig', [
                'category' => $category,
            ]);
        }

        if (!$categoryName = $request->request->get('categoryName', null)) {
            // Display a error message
            $this->addFlash( 'danger', 'Please, enter a name to the category' );
            return $this->render('category/edit.html.twig', [
                'category' => $category,
            ]);
        }

        if (!$categoryColour = $request->request->get('categoryColour', null)) {
            // Display a error message
            $this->addFlash( 'danger', 'Please, enter a colour to the category' );
            return $this->render('category/edit.html.twig', [
                'category' => $category,
            ]);
        }

        // Set fields of the category
        $category->setName($categoryName);
        $category->setColour($categoryColour);
        // Save category
        $entityManager->persist($category);
        $entityManager->flush();
        // Display a success message
        $this->addFlash( 'success', 'The category has been updated' );
        // Redirect to list of categories
        return $this->redirectToRoute('app_categories_get');
    }

    /**
     * @Route("/categories/remove/{id}", name="app_category_remove")
     */
    public function removeCategory(Category $category, Request $request)
    {
        // Remove category
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($category);
        $entityManager->flush();
        // Display a success message
        $this->addFlash( 'success', 'The category has been removed' );
        // Redirect to list of categories
        return $this->redirectToRoute('app_categories_get');
    }
}
