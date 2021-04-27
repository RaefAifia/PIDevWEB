<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Evenement;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\Oeuvrage;
use App\Entity\Panier;
use App\Entity\Reclamation;
use App\Entity\Reservation;
use App\Form\ReclamationType;
use App\Form\ReclamationType1;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findBy(["user" => $this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="reclamation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reclamation ->setDate(new \DateTime('now'));
            $reclamation ->setUser($user = $this->getUser());
            $reclamation ->setAvertissement(0);
            $user = $this->getUser();
            $x=$reclamation-> getX();
            if($x==="Oeuvre"){
                $repository = $this->getDoctrine()->getRepository(Oeuvrage::class);
                $repository1 = $this->getDoctrine()->getRepository(Commande::class);
                $repository2 = $this->getDoctrine()->getRepository(Panier::class);

                $Oeuvrage = $repository-> findOneBy(["nom" => $reclamation ->getconcernant()]);
                $Commande = $repository1->findOneBy(["user" => $user ]);
                $Panier =$repository2->findOneBy(["commande"=> $Commande , "oeuvrage" => $Oeuvrage]);
                if(!$Panier){
                    $this->addFlash('error', 'Vous devez commander l"oeuvre avant de réclamer');
                }
                else{
                $reclamation-> setOeuvrage($Oeuvrage )     ;
                    $entityManager->persist($reclamation);
                    $entityManager->flush();
                    return $this->redirectToRoute('reclamation_index');}
            }
            elseif($x==="Formation"){
                $repository3 = $this->getDoctrine()->getRepository(Formation::class);
                $repository4 = $this->getDoctrine()->getRepository(Inscription::class);

                $Formation= $repository3-> findOneBy(["titre" => $reclamation ->getconcernant()]);

                $Inscription= $repository4-> findOneBy(["formation" => $Formation , "user"=> $user]);

                if(!$Inscription){ $this->addFlash('error', 'Vous devez vous inscrire avant de réclamer');}
                else{
                $reclamation-> setFormation($Formation)    ;
                $entityManager->persist($reclamation);
                $entityManager->flush();
                    return $this->redirectToRoute('reclamation_index');}
            }
            elseif($x==="Evenement"){
                $repository5 = $this->getDoctrine()->getRepository(Evenement::class);
                $repository6 = $this->getDoctrine()->getRepository(Reservation::class);

                $Evenement= $repository5-> findOneBy(["titre" => $reclamation ->getconcernant()]);
                $Reservation= $repository6-> findOneBy(["evenement" => $Evenement ,"user"=> $user]);
                if(!$Reservation){
                    $this->addFlash('error', 'Vous devez réserver l"évenement avant de réclamer');
                }else{

                $reclamation-> setEvenement($Evenement)    ;
                $entityManager->persist($reclamation);
                $entityManager->flush();
                return $this->redirectToRoute('reclamation_index');}
            }



        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{reclamationId}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repository0 = $this->getDoctrine()->getRepository(Formation::class);
        $repository1 = $this->getDoctrine()->getRepository(Evenement::class);

        $repository = $this->getDoctrine()->getRepository(Oeuvrage::class);
        if($reclamation->getOeuvrage()!=null){
            $Oeuvrage = $repository-> find($reclamation->getOeuvrage());
            $reclamation->setX("Oeuvre");
            $reclamation ->setconcernant($Oeuvrage->getNom());
        }
        if($reclamation->getFormation()!=null){
            $Formation = $repository0-> find($reclamation->getFormation());
            $reclamation->setX("Formation");
            $reclamation ->setconcernant($Formation->getTitre());
        }
        if($reclamation->getEvenement()!=null){
            $Evenement = $repository1-> find($reclamation->getEvenement());
            $reclamation->setX("Evenement");
            $reclamation ->setconcernant($Evenement->getTitre());
        }

        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{reclamationId}/edit", name="reclamation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reclamation $reclamation): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(ReclamationType1::class, $reclamation);
        $form->handleRequest($request);
        $repository0 = $this->getDoctrine()->getRepository(Formation::class);
        $repository1 = $this->getDoctrine()->getRepository(Evenement::class);

        $repository = $this->getDoctrine()->getRepository(Oeuvrage::class);
        if($reclamation->getOeuvrage()!=null){
            $Oeuvrage = $repository-> find($reclamation->getOeuvrage());
            $reclamation->setX("Oeuvre");
            $reclamation ->setconcernant($Oeuvrage->getNom());
        }
        if($reclamation->getFormation()!=null){
            $Formation = $repository0-> find($reclamation->getFormation());
            $reclamation->setX("Formation");
            $reclamation ->setconcernant($Formation->getTitre());
        }
        if($reclamation->getEvenement()!=null){
            $Evenement = $repository1-> find($reclamation->getEvenement());
            $reclamation->setX("Evenement");
            $reclamation ->setconcernant($Evenement->getTitre());
        }

       if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reclamation_index');
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{reclamationId}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$reclamation->getReclamationId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        if($this->getUser()->isAdmin()){
            return $this->redirectToRoute('admin_reclamation');
        }
        else{
            return $this->redirectToRoute('reclamation_index');
        }
    }
}
