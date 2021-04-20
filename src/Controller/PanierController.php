<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Panier;
use App\Entity\PanierTemp;
use App\Form\CommandeType;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pan")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     */
    public function index(): Response
    {
        $paniers = $this->getDoctrine()
            ->getRepository(Panier::class)
            ->findAll();

        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers,
        ]);
    }

    /**
     * @Route("/new", name="panier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
        $panierTemps = $this->getDoctrine()
            ->getRepository(PanierTemp::class)
            ->findBy(['user'=>1]);
        $livraisons = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findBy(['user'=>1]);
        $prix = 0;
        foreach ($panierTemps as $p){
            $prix = $prix + ($p->getQuantite()*$p->getOeuvrage()->getPrix());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery("SELECT c FROM App\Entity\Commande c WHERE c.commandeId = 17");
            $commande = $query->getSingleResult();
            $panier->setCommande($commande);
            $query = $em->createQuery("SELECT p FROM App\Entity\User p WHERE p.userId = 1");
            $oeuvrage = $query->getResult();
            $panier->getOeuvrage($oeuvrage);
            $query = $em->createQuery("SELECT q FROM App\Entity\PanierTemp q WHERE q.quantite = 1");
            $quantite = $query->getResult();
            $panier->setQuantite($quantite);
            $em->persist($panier);
            $em->flush();

            return $this->redirectToRoute('panier_temp_index');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'panier_temps' => $panierTemps,
            'livraisons' => $livraisons,
            'prix' => $prix,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", name="panier_show", methods={"GET"})
     */
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="panier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Panier $panier): Response
    {
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_index');
        }

        return $this->render('panier/edit.html.twig', [
            'panier' => $panier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_delete", methods={"POST"})
     */
    public function delete(Request $request, Panier $panier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_index');
    }
}
