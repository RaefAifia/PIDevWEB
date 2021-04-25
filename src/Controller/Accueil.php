<?php


namespace App\Controller;


use App\Entity\Oeuvrage;
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
    public function index(): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->affaccueuil();
      //  $rep=$this->getDoctrine()->getRepository(Formation::class)->affaccueuilF();

        return $this->render('base.html.twig', [
            'oeuvrages' => $oeuvrages,
          //  'formations' => $rep,
        ]);
    }
}