<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/livraison")
 */
class LivraisonController extends AbstractController
{
    /**
     * @Route("/", name="livraison_index", methods={"GET"})
     */
    public function index(): Response
    {
        $livraisons = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findBy(['user'=>1]);

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }
    /**
     * @Route("/admin", name="livraison_indexadmin", methods={"GET"})
     */
    public function indexadmin(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/indexadmin.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livraison_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery("SELECT u FROM App\Entity\User u WHERE u.userId = 1");
            $user = $query->getSingleResult();
            $livraison->setUser($user);
            $query = $em->createQuery("SELECT c FROM App\Entity\Commande c WHERE c.commandeId = 20");
            $commande = $query->getSingleResult();
            $livraison->setCommande($commande);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('panier_new');
        }

        return $this->render('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admrecherchelivr", name="admrecherchelivr")
     */
    public function searchLivAction(Request $request,LivraisonRepository $repository){
        $em = $this->getDoctrine()->getManager();


        $searchParameter = $request->get('livraison');
        if(strlen($searchParameter)==0)
            $entities = $em->getRepository(Livraison::class)->findAll();
        else

            //call repository function

            $entities = $repository->findByExpField($searchParameter);



        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entities, 'json',['ignored_attributes'=>['Commande','User']

        ]);

        $response = new Response(json_encode($jsonContent));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');

        return $response;
    }

    /**
     * @Route("/{livraisonId}", name="livraison_show", methods={"GET"})
     */
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }
    /**
     * @Route("/admin/{livraisonId}", name="livraison_showadmin", methods={"GET"})
     */
    public function showadmin(Livraison $livraison): Response
    {
        return $this->render('livraison/showadmin.html.twig', [
            'livraison' => $livraison,
        ]);
    }


    /**
     * @Route("/{livraisonId}/edit", name="livraison_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livraison $livraison): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livraison_indexadmin');
        }

        return $this->render('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{livraisonId}", name="livraison_delete", methods={"POST"})
     */
    public function delete(Request $request, Livraison $livraison): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getLivraisonId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livraison_index');
    }
}
