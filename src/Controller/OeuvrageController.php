<?php

namespace App\Controller;

use App\Entity\Oeuvrage;
use App\Form\OeuvrageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/oeuvrage")
 */
class OeuvrageController extends AbstractController
{
    /**
     * @Route("/", name="oeuvrage_index", methods={"GET"})
     */
    public function index(): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findAll();

        return $this->render('oeuvrage/index.html.twig', [
            'oeuvrages' => $oeuvrages,
        ]);
    }

    /**
     * @Route("/new", name="oeuvrage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $oeuvrage = new Oeuvrage();
        $form = $this->createForm(OeuvrageType::class, $oeuvrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oeuvrage);
            $entityManager->flush();

            return $this->redirectToRoute('oeuvrage_index');
        }

        return $this->render('oeuvrage/new.html.twig', [
            'oeuvrage' => $oeuvrage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{oeuvrageId}", name="oeuvrage_show", methods={"GET"})
     */
    public function show(Oeuvrage $oeuvrage): Response
    {
        return $this->render('oeuvrage/show.html.twig', [
            'oeuvrage' => $oeuvrage,
        ]);
    }

    /**
     * @Route("/{oeuvrageId}/edit", name="oeuvrage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Oeuvrage $oeuvrage): Response
    {
        $form = $this->createForm(OeuvrageType::class, $oeuvrage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('oeuvrage_index');
        }

        return $this->render('oeuvrage/edit.html.twig', [
            'oeuvrage' => $oeuvrage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{oeuvrageId}", name="oeuvrage_delete", methods={"POST"})
     */
    public function delete(Request $request, Oeuvrage $oeuvrage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvrage->getOeuvrageId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($oeuvrage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('oeuvrage_index');
    }
}
