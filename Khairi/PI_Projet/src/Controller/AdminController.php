<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Form\UtildashType;
use App\Form\UtilisateurType;

use App\Repository\EvenementRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request ;
use Doctrine\Persistence\ManagerRegistry ;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="global")
     */
    public function index(EvenementRepository$repository): Response
    {

        return $this->render('Front/home.html.twig', [
            'evenements_approuver' => $repository->findOneBySomeField(1,1),
        ]);

    }

    /**
     * @Route("/admin", name="back")
     */
    public function aff(): Response
    {
        return $this->render('dashboard.html.twig');
    }




}
