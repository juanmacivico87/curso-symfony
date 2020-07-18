<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Form\BookmarkType;
use App\Repository\BookmarkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkController extends AbstractController
{
    /**
     * @Route("/bookmarks", name="app_bookmark_get", methods={"GET"})
     */
    public function index(BookmarkRepository $bookmarkRepository): Response
    {
        return $this->render('bookmark/index.html.twig', [
            'bookmarks' => $bookmarkRepository->findAll(),
        ]);
    }

    /**
     * @Route("/bookmarks/new", name="app_bookmark_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bookmark = new Bookmark();
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bookmark);
            $entityManager->flush();

            return $this->redirectToRoute('app_bookmark_get');
        }

        return $this->render('bookmark/new.html.twig', [
            'bookmark' => $bookmark,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bookmarks/edit/{id}", name="app_bookmark_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bookmark $bookmark): Response
    {
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_bookmark_get');
        }

        return $this->render('bookmark/edit.html.twig', [
            'bookmark' => $bookmark,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/bookmarks/delete/{id}", name="app_bookmark_delete", methods={"GET"})
     */
    public function delete(Request $request, Bookmark $bookmark): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($bookmark);
        $entityManager->flush();

        return $this->redirectToRoute('app_bookmark_get');
    }
}
