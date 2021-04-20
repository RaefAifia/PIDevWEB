<?php

namespace App\Controller;

use App\Entity\Relations;
use App\Entity\User;
use App\Form\Registration;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findBy(["validite"=>1]),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setAvertissement(0);
            $user->setValidite(1);
            $user->setMailconfirme(0);
            $user->setNumconfirme(0);
            $user->setRole("client");

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }


        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{userId}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        $relation = $this->getDoctrine()
            ->getRepository(Relations::class)
            ->findOneBy(['followee'=>$user ,'follower'=> $this->getUser() ]);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'relation' =>$relation,
        ]);
    }


    /**
     * @Route("/{userId}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{userId}/delete", name="user_delete", methods={"POST"} )
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getUserId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setValidite(0);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
    /**
     * @Route("/{userId}/relation", name="user_sabonner",  methods={"GET","POST"})
     */
    public function Relation (Request $request, User $user): Response
    {
        $relation = $this->getDoctrine()
            ->getRepository(Relations::class)
            ->findOneBy(['followee'=>$user ,'follower'=> $this->getUser() ]);
        if  (!$relation ){
            $response = $this->forward('App\Controller\RelationsController::newR', [
                'user'  => $user,

            ]);
            return $response;
        }
        else { $response = $this->forward('App\Controller\RelationsController::deleteR', [
            'relation' =>$relation,
            'user' => $user,
        ]);
            return $response;

        }

    }
}
