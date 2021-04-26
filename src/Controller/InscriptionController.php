<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\User;
use App\Entity\Formation;
use App\Form\InscriptionType;
use App\Repository\FormationRepository;
use App\Repository\InscriptionRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/inscription")
 */
class InscriptionController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement", methods={"GET","POST"})
     */
    public function index()
    {$formation= new Formation();
        return $this->render('inscription/paiement.html.twig',[
                'formation' => $formation,
            ]

        );
    }


    /**
     * @Route("/success/{formationId}", name="success", methods={"GET"})
     */
    public function success(Formation $formation)
    {
        //return $this->redirectToRoute('formation_cours_index',array('id' => $formation->getFormationId()));
       return $this->render('inscription/success.html.twig', [
            'formation' => $formation,
        ]);
    }
    /**
     * @Route("/error", name="error", methods={"GET"})
     */
    public function error(): Response
    {
        return $this->render('inscription/error.html.twig');
    }

    /**
     * @Route("/create-checkout-session", name="checkout",methods={"GET","POST"})
     * @param $request
     */
    public function checkout(Request $request,FormationRepository $formationRepository)
    {


     // dump($request->query->get('formationId'));
 $formationId= $request->query->get('formationId');
//     //  $formationId= $_GET['formationId'];
//       // dump($formationId);
$entityManager = $this->getDoctrine()->getManager();
//dump("SELECT f FROM App\Entity\Formation f WHERE f.formationId =" .$formationId);
    $query = $entityManager->createQuery("SELECT f FROM App\Entity\Formation f WHERE f.formationId ="  .$formationId);
     $formation = $query->getSingleResult();
        $prix=$formation->getPrix();
//      $formation=$formation->setFormationId($formationId);
// $formation=$formationRepository->findBy($formationId);


//        for ($i = 0; $i <= count($formations); $i++) {
//            $formation=$formations[$i];
//            $prix=$formation->getPrix();
//
//       }

        /*
         // $prix=array('prix' => $formation->getPrix());

            //  $prix=$prix+$formation->getPrix();*/



          \Stripe\Stripe::setApiKey('sk_test_51IjMKsJAixYnURnKfkzrJEukR34BHYai7dmnJsj7FoVbf2OMMMGfPghW4CpCHwDrp3oSFLw9neSr97kBKNLi21q900e8QJI3kp');

          $session = \Stripe\Checkout\Session::create([
              'payment_method_types' => ['card'],
              'line_items' => [[
                  'price_data' => [
                      'currency' => 'eur',
                      'product_data' => [
                          'name' => 'Formation à payer',
                      ],
                      'unit_amount' => $prix*100,
                  ],
                  'quantity' => 1,
              ]],
              'mode' => 'payment',
              'success_url' => $this->generateUrl('success', ['formationId' => $formationId], UrlGeneratorInterface::ABSOLUTE_URL),
              'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return new JsonResponse([ 'id' => $session->id ]);

          /*return $this->render('inscription/index.html.twig', [
              'inscriptions' => $inscriptionRepository->findAll(),
          ]);*/
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
            $user =$this->getUser();
            $inscription->setUser($user);

            $inscription->setIsinscrit(1);

            $query = $entityManager->createQuery("SELECT f FROM App\Entity\Formation f WHERE f.formationId = :id");
            $query->setParameter('id',$request->attributes->get('id'));
            $formation = $query->getSingleResult();
            $inscription->setFormation($formation);


            $entityManager->persist($inscription);
            $entityManager->flush();

           // return $this->redirectToRoute('formation_cours_index',array('id' => $formation->getFormationId()));
            return $this->redirectToRoute('paiement',array('formationId' => $formation->getFormationId()));
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
