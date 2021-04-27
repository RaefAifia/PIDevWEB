<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\User;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use App\Repository\CommandeRepository;
use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $livraisons = $this->getDoctrine()
            ->getRepository(Livraison::class)
            ->findBy(['user'=>$u]);

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }
    /**
     * @Route("/admin", name="livraison_indexadmin", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexadmin(LivraisonRepository $livraisonRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('livraison/indexadmin.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livraison_new", methods={"GET","POST"})
     */
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $livraison->setUser($u);
            $commande = $this->getDoctrine()->getRepository(Commande::class)
                ->findnvc();
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
     * @Route("/Livreur/{id}", name="livreurs", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function livreur(Request $request, int $id, Livraison $livraison): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
            $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['isLivreur' => 1]);

        return $this->render('livraison/livreurs.html.twig', [
            'livraison' => $livraison,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/Confirmation/{id}/{livreurid}", name="confirmationliv", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function confirmation(Request $request,$id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $livraisons = $entityManager->getRepository(Livraison::class)->findAll();
        $livraison = $entityManager->getRepository(Livraison::class)->find($id);
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['isLivreur' => 1]);
        $query = $entityManager->createQuery("SELECT o FROM App\Entity\User o WHERE o.userId = :livreurid");
        $query->setParameter('livreurid',$request->attributes->get('livreurid'));
        $livreur = $query->getSingleResult();
        $livraison->setLivreur($livreur);
        $livraison->setEtat('En Cours');
        $entityManager->flush();


        return $this->render('livraison/indexadmin.html.twig', [
            'livraison' => $livraison,
            'livraisons' => $livraisons,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/PanelLivreur", name="panellivreur", methods={"GET","POST"})
     * @IsGranted("ROLE_LIVREUR")
     */
    public function livreurindex(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $livraisons = $entityManager->getRepository(Livraison::class)->findBy(['livreur'=>$u]);

        return $this->render('livraison/PanelLivreur.html.twig', [
            'livraisons' => $livraisons,
        ]);
    }

    /**
     * @Route("/PanelLivreur/{id}", name="panellivreureff", methods={"GET","POST"})
     * @IsGranted("ROLE_LIVREUR")
     */
    public function livdone(Request $request,$id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $u = $this->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $livraisons = $entityManager->getRepository(Livraison::class)->findBy(['livreur'=>$u]);
        $livraison = $entityManager->getRepository(Livraison::class)->find($id);
        $livraison->setEtat('Livré');
        $entityManager->flush();


        return $this->render('livraison/PanelLivreur.html.twig', [
            'livraison' => $livraison,
            'livraisons' => $livraisons,
        ]);
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
     * @Route("/admin/{livraisonId}", name="livraison_showadmin", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function showadmin(Livraison $livraison): Response
    {
        return $this->render('livraison/livreurs.html.twig', [
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
