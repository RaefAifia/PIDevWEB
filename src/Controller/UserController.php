<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Reclamation;
use App\Entity\Relations;
use App\Entity\User;
use App\Form\Emailconfirm;
use App\Form\Registration;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController

{
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }
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
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder , AppCustomAuthenticator $login, GuardAuthenticatorHandler $guard): Response
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
            $user->setImage(null);
            $user->setImageFile(null);
            return $guard->authenticateUserAndHandleSuccess($user,$request,$login,'main');


            return $this->redirectToRoute("contact");
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
    public static function send(){
        $code = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
echo($code);
        $message = (new \Swift_Message('Votre code de confirmation'))
            ->setFrom('mariem.arif@esprit.tn')
            ->setTo('mariema020@gmail.com')
            ->setBody(
                'Votre code de validation est :' . $code,
                'text/plain'
            );

        /*$mailer->send($message);*/
        return $code;
    }

    /**
     * @Route("/a/{userId}/contact", name="contact", methods={"GET","POST"})
     */

    public function sendMail(Request $request, \Swift_Mailer $mailer , User $user ): Response
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getUserId(),
            $user->getEmail()
        );
        $email = new TemplatedEmail();
        $email->from('mariem.arif@esprit.tn');
        $email->to('mariema020@gmail.com');
        $email->htmlTemplate('user/confirmermail.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);


        return $this->redirectToRoute('user_index');

    }
    /**
     * @Route("/a/verify", name="registration_confirmation_route")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        // Do not get the User's Id or Email Address from the Request object
        try {
            $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getUserId(), $user->getEmail());
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('verify_email_error', $e->getReason());

            return $this->redirectToRoute('login');
        }

        // Mark your user as verified. e.g. switch a User::verified property to true

        $this->addFlash('success', 'Your e-mail address has been verified.');

        $user->getUserId();

        $user->setMailconfirme(1);
        $this->getDoctrine()->getManager()->flush();



        return $this->redirectToRoute('user_index');
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
