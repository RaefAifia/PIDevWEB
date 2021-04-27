<?php

namespace App\Controller;


use App\Entity\FavorisO;
use App\Entity\Relations;
use App\Entity\User;
use App\Form\Registration;
use App\Form\UserType;
use App\Form\UserEdit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Routing\Annotation\Route;


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

        $queryBuilder = $userRepository
            ->createQueryBuilder('c');

        $result = $queryBuilder->select('c')
            ->where('c.isFormateur =1  or c.isVendeur =1 ')
            ->andWhere('c.validite=1')

            ->getQuery()
            ->getResult();
        return $this->render('user/index.html.twig', [
            'users' => $result,
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
        $relationx = $this->getDoctrine()
            ->getRepository(Relations::class)
            ->findBy(['followee' => $user]);
        $fav = $this->getDoctrine()
            ->getRepository(FavorisO::class)
            ->findBy(['user' => $user]);



        return $this->render('user/show.html.twig', [
            'user' => $user,
            'relation' =>$relation,
            'relationx'=>count($relationx),
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $user->getUserId(),
            $user->getEmail()
        );
        $email = new TemplatedEmail();
        $email->from('mariem.arif@esprit.tn');
        $email->to('yosra.mahjoub@esprit.tn');
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
     * @Route("/a/{userId}/contactSms", name="contactSMS", methods={"GET","POST"})
     */

    public function sendSMS(Request $request ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $country=216;

        if ( $country ) {

            $authy_api = new \Authy\AuthyApi( '0Rkixl3ya2f6kC6n3TBjwBxyHWxfe9P4');
            $user      = $authy_api->registerUser( 'mariema020@gmail.com', '20246474', '216');
$s = $user->id();
            if ( $user->ok() ) {


                $sms = $authy_api->requestSms( $user->id(), [ "force" => "true" ] );

                if ( $sms->ok() ) {

                    $this->addFlash(
                        'success',
                        $sms->message()
                    );
                }

                $user_params = [
                    'username' => $request->request->get('username'),
                    'email' => $request->request->get('email'),
                    'country_code' => "216",
                    'phone_number' => "20246474",
                    'password' => $request->request->get('password'),
                    'authy_id' => $user->id(),
                ];

                $this->get('session')->set('user', $user_params);
            }


        }

        return $this->redirectToRoute('verify_page');

    }
    /**
     * @Route("/verify/page", name="verify_page")
     */
    public function verifyCodePage()
    {
        return $this->render('user/confirmSMS.html.twig');
    }
    /**
     * @Route("/verify/code", name="verify_code")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function verifyCode(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        try {
            // Get data from session
            $data = $this->get('session')->get('user');

            $authy_api    = new \Authy\AuthyApi( 'wlTNObASocHt0NMtRwrOkBwCVxD1NBXm' );
            $verification = $authy_api->verifyToken( $data['authy_id'], $request->query->get('verify_code') );

            $this->addFlash(
                'success',
                'You phone number has been verified.'
            );
            $user= $this->getUser();


            $user->setNumconfirme(1);
            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('user_index');


        } catch (\Exception $exception) {
            $this->addFlash(
                'error',
                'Verification code is incorrect'
            );
            return $this->redirectToRoute('verify_page');
        }
    }


    /**
     * @Route("/{userId}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(UserEdit::class, $user);
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
     * @Route("/{userId}/delete", name="user_delete", methods={"GET","POST"} )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$user->getUserId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setValidite(0);
            $entityManager->flush();
            return $this->redirectToRoute('app_logout');
        }
        return $this->redirectToRoute('user_index');


    }
    /**
     * @Route("/{userId}/relation", name="user_sabonner",  methods={"GET","POST"})
     */
    public function Relation (Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
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
