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
     * @Route("tags/", name="app_tags_get")
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    /**
     * @Route("tags/search", name="app_tags_search")
     */
    public function search(TagRepository $tagRepository, Request $request): Response
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
    public function new(Request $request): Response
    {
        $tag = new Tag();

        if (!$this->isCsrfTokenValid('tag', $request->request->get('tagToken'))) {
            return $this->render('tag/new.html.twig', [
                'tag' => $tag,
            ]);
        }

        if (!$tagName = $request->request->get('tagName', null)) {
            $this->addFlash( 'danger', 'Please, enter a name to the new tag' );
            return $this->render('tag/new.html.twig', [
                'tag' => $tag,
            ]);
        }

        $tag->setName($tagName);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tag);
        $entityManager->flush();
        $this->addFlash( 'success', 'The new tag has been saved' );
        
        return $this->redirectToRoute('app_tags_get');
    }

    /**
     * @Route("tags/new/ajax", name="app_tag_new_ajax")
     */
    public function newFromAjax(Request $request)
    {
        if (false === $request->isXmlHttpRequest())
            throw $this->createNotFoundException();

        $tag    = new Tag();
        $form   = $this->createForm(TagType::class, $tag, [
            'action' => $this->generateUrl('app_tag_new_ajax'),
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'isCreated' => false,
                'form'      => $this->render('tag/_form.html.twig', [
                    'ajax'  => true,
                    'form'  => $form->createView(),
                ]),
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tag);
        $entityManager->flush();
        $this->addFlash('success', 'The new tag has been saved');

        $tag    = new Tag();
        $form   = $this->createForm(TagType::class, $tag, [
            'action' => $this->generateUrl('app_tag_new_ajax'),
        ]);

        return $this->json([
            'isCreated' => true,
            'form'      => $this->render('tag/_form.html.twig', [
                'ajax'  => true,
                'form'  => $form->createView(),
            ]),
        ]);
    }

    /**
     * @Route("tags/edit/{id}", name="app_tag_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tag $tag): Response
    {
        if (!$this->isCsrfTokenValid('tag', $request->request->get('tagToken'))) {
            return $this->render('tag/edit.html.twig', [
                'tag' => $tag,
            ]);
        }

        if (!$tagName = $request->request->get('tagName', null)) {
            $this->addFlash( 'danger', 'Please, enter a name to the tag' );
            return $this->render('tag/edit.html.twig', [
                'tag' => $tag,
            ]);
        }

        $tag->setName($tagName);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tag);
        $entityManager->flush();
        $this->addFlash( 'success', 'The tag has been updated' );
        
        return $this->redirectToRoute('app_tags_get');
    }

    /**
     * @Route("tags/remove/{id}", name="app_tag_remove", methods={"GET"})
     */
    public function delete(Request $request, Tag $tag): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tag);
        $entityManager->flush();
        $this->addFlash( 'success', 'The tag has been removed' );

        return $this->redirectToRoute('app_tags_get');
    }
}
