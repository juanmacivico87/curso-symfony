<?php

namespace App\Controller;

use App\Repository\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(BookmarkRepository $bookmarkRepository)
    {
        return $this->render('index/index.html.twig', [
            'bookmarks' => $bookmarkRepository->findAll(),
        ]);
    }
}
