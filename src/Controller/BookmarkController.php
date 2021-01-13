<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Form\BookmarkType;
use App\Form\SearchType;
use App\Repository\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkController extends AbstractController
{
    /**
     * @Route("/bookmarks/new", name="app_bookmark_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bookmark = new Bookmark();
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('bookmark/new.html.twig', [
                'bookmark' => $bookmark,
                'form' => $form->createView(),
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($bookmark);
        $entityManager->flush();
        $this->addFlash( 'success', 'The new bookmark has been saved' );

        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * @Route("/bookmarks/edit/{id}", name="app_bookmark_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bookmark $bookmark): Response
    {
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('bookmark/edit.html.twig', [
                'bookmark' => $bookmark,
                'form' => $form->createView(),
            ]);
        }

        $this->getDoctrine()->getManager()->flush();
        $this->addFlash( 'success', 'The bookmark has been updated' );

        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * @Route("/bookmarks/delete/{id}", name="app_bookmark_delete", methods={"GET"})
     */
    public function delete(Request $request, Bookmark $bookmark): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($bookmark);
        $entityManager->flush();
        $this->addFlash( 'success', 'The bookmark has been removed' );

        return $this->redirectToRoute('app_dashboard');
    }

    /**
     * @Route("/bookmarks/search", name="app_search")
     */
    public function search(BookmarkRepository $bookmarkRepository, Request $request)
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $searchString = '';

        if ($form->isSubmitted() && $form->isValid())
            $searchString = $form->get('searchInput')->getData();

        $bookmarks  = $bookmarkRepository->findByName($searchString);

        if (false === empty($bookmarks) && $form->isSubmitted()) {
            return $this->render('index/index.html.twig', [
                'bookmarks'     => $bookmarks,
                'search_form'   => $form->createView(),
            ]);
        }

        return $this->render('partials/_search.html.twig', [
            'search_form'   => $form->createView(),
        ]);
    }
}
