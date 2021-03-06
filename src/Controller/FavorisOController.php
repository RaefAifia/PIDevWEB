<?php

namespace App\Controller;

use App\Entity\FavorisO;
use App\Entity\Oeuvrage;
use App\Entity\User;
use App\Form\FavorisOType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/favoris/o")
 */
class FavorisOController extends AbstractController
{
    /**
     * @Route("/", name="favoris_o_index", methods={"GET"})

     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $favoris = $this->getDoctrine()
            ->getRepository(FavorisO::class)
            ->findBy(['user'=>$user]);
        $oeuvrages = array();
        foreach ($favoris AS $f) {

            $oeuvrages[$f->getFavorisOId()] = $f->getOeuvrage();
        }
        $listfavo = $paginator->paginate (
            $oeuvrages, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6// Nombre de résultats par page
        );


        return $this->render('oeuvrage/listfav.html.twig', [
            'oeuvrages' => $listfavo,
        ]);
    }

    /**
     * @Route("/new", name="favoris_o_new", methods={"GET","POST"})
     */
    public function newf(  $user,  $oeuvrage): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $favorisO = new FavorisO();
        $favorisO ->setUser($user);
        $favorisO ->setOeuvrage($oeuvrage);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($favorisO);
            $entityManager->flush();


        return $this->redirectToRoute('oeuvrage_show', [
            'oeuvrageId' => $oeuvrage->getOeuvrageId(),
        ]);
    }

    /**
     * @Route("/{favorisOId}", name="favoris_o_show", methods={"GET"})
     */
    public function show(FavorisO $favorisO): Response
    {
        return $this->render('favoris_o/show.html.twig', [
            'favoris_o' => $favorisO,
        ]);
    }

    /**
     * @Route("/{favorisOId}/edit", name="favoris_o_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FavorisO $favorisO): Response
    {
        $form = $this->createForm(FavorisOType::class, $favorisO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('favoris_o_index');
        }

        return $this->render('favoris_o/edit.html.twig', [
            'favoris_o' => $favorisO,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{favorisOId}", name="favoris_o_delete", methods={"POST"})
     */
    public function delete( $favoris, $oeuvrage): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($favoris);
            $entityManager->flush();
            return $this->redirectToRoute('oeuvrage_show', [
            'oeuvrageId' => $oeuvrage->getOeuvrageId(),

        ]);
    }
}
