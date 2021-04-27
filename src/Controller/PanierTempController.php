<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Oeuvrage;
use App\Entity\PanierTemp;
use App\Entity\Panier;
use App\Form\CommandeType;
use App\Form\PanierTempType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $u = $this->getUser();
        $panierTemps = $this->getDoctrine()
            ->getRepository(PanierTemp::class)
            ->findBy(['user'=>$u]);

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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $panierTemp = new PanierTemp();
        $panierTemps = $this->getDoctrine()
            ->getRepository(PanierTemp::class)
            ->findBy(['user'=>$u]);
        $form = $this->createForm(PanierTempType::class, $panierTemp);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $panierTemp->setUser($u);

            $query = $entityManager->createQuery("SELECT o FROM App\Entity\Oeuvrage o WHERE o.oeuvrageId = :id");
            $query->setParameter('id',$request->attributes->get('id'));
            $oeuvrage = $query->getSingleResult();
            $panierTemp->setOeuvrage($oeuvrage);

                $p = $this->getDoctrine()->getRepository(PanierTemp::class)
                    ->findByExampleField($request->attributes->get('id'),$u);
            if($p){
                $this->addFlash('red', 'Element existe! Veuillez modifier la quantité');
                return $this->redirectToRoute('panier_temp_new',['id'=>$request->attributes->get('id')]);

            }elseif($panierTemp->getQuantite()>$panierTemp->getOeuvrage()->getQuantite()){
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('red', 'Stock Insuffisant! Veuillez saisir la bonne quantité');

                return $this->redirectToRoute('panier_temp_new',['id'=>$request->attributes->get('id')]);
            }else{
                $this->addFlash('green', 'Element ajouté avec succès!');
                $entityManager->persist($panierTemp);
                $entityManager->flush();
                return $this->redirectToRoute('commande_new');
            }

        }

        return $this->render('panier_temp/new.html.twig', [
            'panier_temp' => $panierTemp,
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(PanierTempType::class, $panierTemp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            {
                if ($panierTemp->getQuantite() > $panierTemp->getOeuvrage()->getQuantite()) {
                    $this->addFlash('red', 'Stock Insuffisant! Veuillez saisir la bonne quantité');
                    return $this->redirectToRoute('panier_temp_edit',['id'=>$request->attributes->get('id')]);
                } else {
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('green', 'Element modifié avec succès!');
                    return $this->redirectToRoute('commande_new');
                }
            }
        };
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$panierTemp->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($panierTemp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande_new');
    }
}
