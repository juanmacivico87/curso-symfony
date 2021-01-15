<?php

namespace App\Controller;

use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public const ITEMS_PER_PAGE = 1;

    /**
     * @Route("/dashboard/favourites/{page}", name="app_favourites", defaults={"categoryName": "All", "page": 1}, requirements={"page"="\d+"})
     */
    public function getFavourites(int $page, BookmarkRepository $bookmarkRepository)
    {
        return $this->render('index/index.html.twig', [
            'bookmarks'         => $bookmarkRepository->findFavourites($page, self::ITEMS_PER_PAGE),
            'page'              => $page,
            'items_per_page'    => self::ITEMS_PER_PAGE,
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
     * @Route("/dashboard/{categoryName}/{page}", name="app_dashboard", defaults={"categoryName": "All", "page": 1}, requirements={"page"="\d+"})
     */
    public function dashboard(string $categoryName, int $page, BookmarkRepository $bookmarkRepository)
    {
        if ('All' !== $categoryName) {
            return $this->render('index/index.html.twig', [
                'bookmarks'         => $bookmarkRepository->findByCategoryName($categoryName, $page, self::ITEMS_PER_PAGE),
                'page'              => $page,
                'items_per_page'    => self::ITEMS_PER_PAGE,
                'category'          => $categoryName,
            ]);
        }

        return $this->render('index/index.html.twig', [
            'bookmarks'         => $bookmarkRepository->findAllWithPagination($page, self::ITEMS_PER_PAGE),
            'page'              => $page,
            'items_per_page'    => self::ITEMS_PER_PAGE,
            'category'          => 'All'
        ]);
    }
}
