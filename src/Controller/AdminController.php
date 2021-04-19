<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Registration;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
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
    /**
     * @Route("/{userId}", name="admin_delete", methods={"GET"})
     */

    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getUserId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setValidite(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_index');
    }


    /**
     * @Route("/Admin/stats" , name="admin_stats")
     */
    public function stat(UserRepository $userRepository){

        $users = $userRepository->findAll();
        $categNom = ["Formateur" , "Vendeur" , "Client"];
        $categColor = ["#a65959" , "#414e4d" , "#343e3d"];

        $categFormateur = count($userRepository->findBy(["isFormateur" =>1]) )  ;
        $categVendeur = count($userRepository->findBy(["isVendeur" =>1]) ) ;
        $categClient = count($userRepository->findBy(["role" => "client"]) ) ;
        $categCount = [$categFormateur , $categVendeur , $categClient ];

        return $this->render('Admin/stat.html.twig', [
            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),

        ]);
    }





}
