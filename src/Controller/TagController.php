<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("tags/", name="app_tags_get", methods={"GET"})
     */
    public function getTags(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("tags/search", name="app_tags_search")
     */
    public function searchTags(TagRepository $tagRepository, Request $request): Response
    {
        if (false === $request->isXmlHttpRequest())
            throw $this->createNotFoundException();
        
        $searchString   = $request->get('q');
        $tags           = $tagRepository->findByName($searchString);

        return $this->json($tags);
    }

    /**
     * @Route("tags/new", name="app_tag_new", methods={"GET","POST"})
     */
    public function createNewTag(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('app_categories_get');
        }

        return $this->render('tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("tags/{id}", name="app_tag_show", methods={"GET"})
     */
    public function showTag(Tag $tag): Response
    {
        return $this->render('tag/show.html.twig', [
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("tags/edit/{id}", name="app_tag_edit", methods={"GET","POST"})
     */
    public function editTag(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_categories_get');
        }

        return $this->render('tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("tags/remove/{id}", name="app_tag_remove", methods={"DELETE"})
     */
    public function removeTag(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categories_get');
    }
}
