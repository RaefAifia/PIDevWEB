<?php


namespace App\Controller;


use App\Entity\Oeuvrage;
use App\Entity\Formation;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */

class Accueil extends AbstractController
{
    /**
     * @Route("/", name="accueil_index", methods={"GET"})

     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->affaccueuil();
        $rep=$this->getDoctrine()->getRepository(Formation::class)->affaccueuilF();

        return $this->render('base.html.twig', [
            'oeuvrages' => $oeuvrages,
            'formations' => $rep,
            'evenements'  => $evenementRepository->findBy(array('etat'=>1),array('evenement_id' => 'DESC'),4),
        ]);
    }
}