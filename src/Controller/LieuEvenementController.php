<?php

namespace App\Controller;

use App\Entity\LieuEvenement;
use App\Form\LieuEvenementType;
use App\Repository\LieuEvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu/evenement")
 */
class LieuEvenementController extends AbstractController
{
    /**
     * @Route("/", name="lieu_evenement_index", methods={"GET"})
     */
    public function index(LieuEvenementRepository $lieuEvenementRepository): Response
    {
        return $this->render('lieu_evenement/index.html.twig', [
            'lieu_evenements' => $lieuEvenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="lieu_evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $lieuEvenement = new LieuEvenement();
        $form = $this->createForm(LieuEvenementType::class, $lieuEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lieuEvenement);
            $entityManager->flush();

            return $this->redirectToRoute('lieu_evenement_index');
        }

        return $this->render('lieu_evenement/new.html.twig', [
            'lieu_evenement' => $lieuEvenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="lieu_evenement_show", methods={"GET"})
     */
    public function show(LieuEvenement $lieuEvenement): Response
    {
        return $this->render('lieu_evenement/show.html.twig', [
            'lieu_evenement' => $lieuEvenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="lieu_evenement_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, LieuEvenement $lieuEvenement): Response
    {
        $form = $this->createForm(LieuEvenementType::class, $lieuEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lieu_evenement_index');
        }

        return $this->render('lieu_evenement/edit.html.twig', [
            'lieu_evenement' => $lieuEvenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="lieu_evenement_delete")
     */
    public function delete(Request $request, LieuEvenement $lieuEvenement): Response
    {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($lieuEvenement);
            $entityManager->flush();


        return $this->redirectToRoute('lieu_evenement_index');
    }
}
