<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Livraison;
use App\Entity\Oeuvrage;
use App\Entity\Panier;
use App\Entity\PanierTemp;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Form\PanierType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {

        $panier = new Panier();
        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);
        $panierTemps = $this->getDoctrine()
            ->getRepository(PanierTemp::class)
            ->findBy(['user'=>1]);
        $livraisons = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findliv();
        $prix = 0;
        foreach ($panierTemps as $p){
            $prix = $prix + ($p->getQuantite()*$p->getOeuvrage()->getPrix());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commande = $this->getDoctrine()->getRepository(Commande::class)
                ->findnvc();

                foreach ($panierTemps as $panierTemp){
                    $panier = new Panier();
                    $panier->setCommande($commande);
                    $panier->setOeuvrage($panierTemp->getOeuvrage());
                    $panier->setQuantite($panierTemp->getQuantite());

                    $em->persist($panier);
                    $em->flush();
                        }
                $panierTemps = $this->getDoctrine()->getRepository(PanierTemp::class)
                    ->deletepant();

            return $this->redirectToRoute('panier_pdf');
        }

        return $this->render('panier/new.html.twig', [
            'panier' => $panier,
            'livraisons' => $livraisons,
            'panier_temps' => $panierTemps,
            'prix' => $prix,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pdf", name="panier_pdf")
     */
    public function facture()
    {
        return $this->render('panier/pdf.html.twig');
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

    /**
     * @Route("/facture/pdfnav", name="panier_pdfnav", methods={"GET"})
     */
    public function pdfnav()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $commandes = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc();
        $commande = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getCommandeId();
        $usernom = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getUser()->getNom();
        $userprenom = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getUser()->getPrenom();
        $frais = 7;
        $prix = $commandes->getPrixtot()+$frais;
        $text = date('d/m/Y à H:i:s ');
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)
            ->findlivr();
        $numtel = $livraison->getNumTel();
        $adresse = $livraison->getAdresse();
        $panier = $this->getDoctrine()->getRepository(Panier::class)
            ->findpan($commande);


        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('panier/facture.html.twig', [
            'title' => "Facture",
            'prix' => $prix,
            'usernom' => $usernom,
            'userprenom' => $userprenom,
            'date' => $text,
            'numtel' => $numtel,
            'adresse' => $adresse,
            'panier' => $panier,
            'frais' => $frais,
            'commande' => $commande
        ]);


        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("Facture.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/facture/pdfgen", name="panier_pdfgen", methods={"GET"})
     */
    public function pdfgen()
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $commandes = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc();
        $commande = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getCommandeId();
        $usernom = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getUser()->getNom();
        $userprenom = $this->getDoctrine()->getRepository(Commande::class)
            ->findnvc()->getUser()->getPrenom();
        $frais = 7;
        $prix = $commandes->getPrixtot()+$frais;
        $text = date('d/m/Y à H:i:s ');
        $livraison = $this->getDoctrine()->getRepository(Livraison::class)
            ->findlivr();
        $numtel = $livraison->getNumTel();
        $adresse = $livraison->getAdresse();
        $panier = $this->getDoctrine()->getRepository(Panier::class)
            ->findpan($commande);



        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('panier/facture.html.twig', [
            'title' => "Facture",
            'prix' => $prix,
            'usernom' => $usernom,
            'userprenom' => $userprenom,
            'date' => $text,
            'numtel' => $numtel,
            'adresse' => $adresse,
            'panier' => $panier,
            'frais' => $frais,
            'commande' => $commande
        ]);


        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream("Facture.pdf", [
            "Attachment" => true
        ]);
    }
}
