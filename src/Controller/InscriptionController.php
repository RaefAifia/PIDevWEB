<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\User;
use App\Entity\Formation;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/", name="inscription_index", methods={"GET"})
     */
    public function index(InscriptionRepository $inscriptionRepository): Response
    {
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptionRepository->findAll(),
        ]);
    }

    /**
     *
     * @Route("/new/{id}", name="inscription_new", methods={"GET","POST"})
     */

    public function new(Request $request): Response
    {/* $rep=$this->getDoctrine()->getRepository(Formation::class);
    find(array(formation:Formation=>getFormationId));*/


        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->find(User::class, 1);
            $inscription->setUser($user);

            $inscription->setIsinscrit(1);

            $query = $entityManager->createQuery("SELECT f FROM App\Entity\Formation f WHERE f.formationId = :id");
            $query->setParameter('id',$request->attributes->get('id')); //à eviter
            $formation = $query->getSingleResult();
            $inscription->setFormation($formation);


            $entityManager->persist($inscription);
            $entityManager->flush();

            return $this->redirectToRoute('formation_cours_index',array('id' => $formation->getFormationId()));
        }

        return $this->render('inscription/new.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
        ]);


    }


    /**
     * @Route("/{inscriptionId}", name="inscription_show", methods={"GET"})
     */
    public function show(Inscription $inscription): Response
    {
        return $this->render('inscription/show.html.twig', [
            'inscription' => $inscription,
        ]);
    }

    /**
     * @Route("/{inscriptionId}/edit", name="inscription_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Inscription $inscription): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('inscription_index');
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{inscriptionId}", name="inscription_delete", methods={"POST"})
     */
    public function delete(Request $request, Inscription $inscription): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscription->getInscriptionId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($inscription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('inscription_index');
    }
    public function findIsinscrit(InscriptionRepository $rep): Boolean
    {
        if ($inscription=$rep->findB(2)){

            return true;
        }


    }
}
