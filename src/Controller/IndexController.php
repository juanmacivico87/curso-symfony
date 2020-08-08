<?php

namespace App\Controller;

use App\Repository\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/dashboard/{categoryName}", name="app_dashboard", defaults={"categoryName": ""})
     */
    public function dashboard(string $categoryName, BookmarkRepository $bookmarkRepository)
    {
        if ('' !== $categoryName)
            return $this->render('index/index.html.twig', [
                'bookmarks' => $bookmarkRepository->findByCategoryName($categoryName),
            ]);

        return $this->render('index/index.html.twig', [
            'bookmarks' => $bookmarkRepository->findAll(),
        ]);
    }
}
