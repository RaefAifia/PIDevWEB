<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\OffreType;
use App\Form\OffreTypeEdit;
use App\Repository\CommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/offre")
 */
class OffreController extends AbstractController
{
    /**
     * @Route("/", name="offre_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        $offres = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findAll();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    /**
     * @Route("/calendar", name="offre_calendar", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function calendar(): Response
    {
        return $this->render('offre/calendar.html.twig');
    }
    /**
     * @Route("/tri", name="tri_offre", methods={"GET"})
     */
    public function indextri(): Response
    {
        $offres = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findtri();

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
        ]);
    }
    /**
     * @Route("/client", name="offre_indexclient", methods={"GET"})
     */
    public function listoffre(Request $request, PaginatorInterface $paginator): Response
    {    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $offres = $this->getDoctrine()
            ->getRepository(Offre::class)
            ->findBy(['user'=>$user]);
        $listoffres = $paginator->paginate (
            $offres, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6// Nombre de résultats par page
        );
        return $this->render('offre/offreclient.html.twig', [
            'offres' => $listoffres,
        ]);
    }

    /**
     * @Route("/new", name="offre_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    /*
     *
     */
    public function new(Request $request): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //$user = $entityManager->find(User::class, 1);

           // $offre->setUser($user);

            $nb = $offre->getNbClient();
            $x=$offre-> getX();


            if($x === "fidèles clients") {
               $cmd =$this->getDoctrine()
                   ->getRepository(Commande::class)
                   ->findfc();
                $users = array();
                foreach ($cmd AS $c) {
                    $users[] = $c->getUser();
                }
            }
            elseif($x === "nouveaux utilisateurs") {
               $users =$this->getDoctrine()
                   ->getRepository(User::class)
                   ->findBy(
                       array(),
                       array('userId' => 'DESC'),
                         $nb);
            }
            elseif($x === "anciens utilisateurs") {
                $users =$this->getDoctrine()
                    ->getRepository(User::class)
                    ->findBy(
                        array(),
                        array('userId' => 'ASC'),
                        $nb);
            }
                for ($i =0 ; $i < $nb ; ++$i) {
                     $u = $users[$i] ;
                      $offre ->setUser($u);
                     $entityManager->persist($offre);
                     $entityManager->flush();
                   $entityManager->clear(Offre::class);
            }
            return $this->redirectToRoute('offre_index');
        }



        return $this->render('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{offreId}", name="offre_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }
    /**
     * @Route("/client/{offreId}", name="offre_showclient", methods={"GET"})
     */
    public function showclient(Offre $offre): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('offre/showoffreclient.html.twig', [
            'offre' => $offre,
        ]);
    }

    /**
     * @Route("/{offreId}/edit", name="offre_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Offre $offre): Response
    {
        $form = $this->createForm(OffreTypeEdit::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{offreId}", name="offre_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Offre $offre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getOffreId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offre_index');
    }
}
