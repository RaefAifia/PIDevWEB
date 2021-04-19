<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Oeuvrage;
use App\Entity\PanierTemp;
use App\Form\CommandeType;
use App\Form\PanierTempType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier/temp")
 */
class PanierTempController extends AbstractController
{
    /**
     * @Route("/", name="panier_temp_index", methods={"GET"})
     */
    public function index(): Response
    {
        $panierTemps = $this->getDoctrine()
            ->getRepository(PanierTemp::class)
            ->findBy(['user'=>1]);

        $prix = 0;
        foreach ($panierTemps as $p){
            $prix = $prix + ($p->getQuantite()*$p->getOeuvrage()->getPrix());
        }

        return $this->render('panier_temp/index.html.twig', [
            'panier_temps' => $panierTemps,
            'prix' => $prix,
        ]);
    }

    /**
     * @Route("/add", name="panier_temp_add", methods={"GET"})
     */
    public function addpanier(): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findAll();

        return $this->render('panier_temp/addpanier.html.twig', [
            'oeuvrages' => $oeuvrages,
        ]);
    }

    /**
     * @Route("/new/{id}", name="panier_temp_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $panierTemp = new PanierTemp();
        $form = $this->createForm(PanierTempType::class, $panierTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $query = $entityManager->createQuery("SELECT u FROM App\Entity\User u WHERE u.userId = 1");
            $user = $query->getSingleResult();
            $panierTemp->setUser($user);
            $query = $entityManager->createQuery("SELECT o FROM App\Entity\Oeuvrage o WHERE o.oeuvrageId = :id");
            $query->setParameter('id',$request->attributes->get('id'));
            $oeuvrage = $query->getSingleResult();
            $panierTemp->setOeuvrage($oeuvrage);
            $entityManager->persist($panierTemp);
            $entityManager->flush();

            return $this->redirectToRoute('panier_temp_index');
        }

        return $this->render('panier_temp/new.html.twig', [
            'panier_temp' => $panierTemp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/newc", name="newc", methods={"GET","POST"})
     */
    public function newp(Request $request): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);
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
            $entityManager = $this->getDoctrine()->getManager();
            $commande->setDate(new \DateTime());
            $commande->setPrixtot($prix);
            $query = $entityManager->createQuery("SELECT u FROM App\Entity\User u WHERE u.userId = 1");
            $user = $query->getSingleResult();
            $commande->setUser($user);
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('livraison_new');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'panier_temps' => $panierTemps,
            'livraisons' => $livraisons,
            'prix' => $prix,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_temp_show", methods={"GET"})
     */
    public function show(PanierTemp $panierTemp): Response
    {
        return $this->render('panier_temp/show.html.twig', [
            'panier_temp' => $panierTemp,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="panier_temp_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PanierTemp $panierTemp): Response
    {
        $form = $this->createForm(PanierTempType::class, $panierTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('panier_temp_index');
        }

        return $this->render('panier_temp/edit.html.twig', [
            'panier_temp' => $panierTemp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="panier_temp_delete", methods={"POST"})
     */
    public function delete(Request $request, PanierTemp $panierTemp): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierTemp->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panierTemp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('panier_temp_index');
    }
}
