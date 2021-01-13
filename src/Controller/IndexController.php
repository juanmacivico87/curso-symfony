<?php

namespace App\Controller;

use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/dashboard/favourites", name="app_favourites")
     */
    public function getFavourites(BookmarkRepository $bookmarkRepository)
    {
        return $this->render('index/index.html.twig', [
            'bookmarks' => $bookmarkRepository->findBy([
                'isFavourite' => true,
            ]),
        ]);
    }

    /**
     * @Route("/dashboard/favourites/edit/{id}", name="app_favourites_edit")
     */
    public function editFavourite(int $id, BookmarkRepository $bookmarkRepository, EntityManagerInterface $em, Request $request)
    {
        if (false === $request->isXmlHttpRequest())
            throw $this->createNotFoundException();
        
        $bookmark = $bookmarkRepository->findOneBy([
            'id' => $id,
        ]);

        if (null === $bookmark)
            throw $this->createNotFoundException();

        $bookmark->setIsFavourite(!$bookmark->getIsFavourite());
        $em->flush($bookmark);

        return $this->json([
            'updated' => true,
        ]);
    }
    
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
