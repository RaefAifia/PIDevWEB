<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Registration;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('Admin/adminutilisateurs.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }



    /**
     * @Route("/{userId}", name="admin_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('Admin/adminShow_user.html.twig', [
            'user' => $user,
        ]);
    }




}
