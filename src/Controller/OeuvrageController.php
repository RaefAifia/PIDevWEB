<?php

namespace App\Controller;

use App\Entity\FavorisO;
use App\Entity\FiltreOeuvre;
use App\Entity\Oeuvrage;
use App\Entity\User;
use App\Form\OeuvrageType;
use App\Repository\OeuvrageRepository;
use App\Repository\UserRepository;
use App\Form\FiltreType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @Route("/oeuvrage")
 */
class OeuvrageController extends AbstractController
{
    /**
     * @Route("/", name="oeuvrage_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $data = new FiltreOeuvre();
        $form = $this->createForm(FiltreType::class, $data);
        $form->handleRequest($request);
        [$min,$max] = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findMinMax($data);

        // $produits = $repository->findSearch($data);
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findsearch($data);

        return $this->render('oeuvrage/index.html.twig', [
            'oeuvrages' => $oeuvrages,
            'form' => $form->createView(),
            'min' => $min,
            'max' => $max
        ]);


    }


  //  /**
    // * @Route("/{nom}", name="oeuvrage_search", methods={"GET"})
    // */
 //   public function recherche($nom): Response
   // {
     //   $oeuvrages = $this->getDoctrine()
       //     ->getRepository(Oeuvrage::class)
         //   ->search($nom);

        // return $this->render('oeuvrage/index.html.twig', [
           //  'oeuvrages' => $oeuvrages,
        // ]);
    //}


    /**
     * @Route("/vendor", name="oeuvrage_indexvendor", methods={"GET"})
     */
    public function indexforvendor( ): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findBy(['user'=>1]);

      //  $user =$this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);

      //  $av = $user->getAvertissement();
        // $conf =$user->getMailconfirme();
        return $this->render('oeuvrage/affoeuvre_vendor.html.twig', [
            'oeuvrages' => $oeuvrages,
           // 'av' => $av,
            //'conf'=>$conf,

        ]);
    }


    /**
     * @Route("/admin", name="oeuvrage_indexa", methods={"GET"})
     */
    public function indexforadmin(): Response
    {
        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findAll();

        return $this->render('oeuvrage/Backadminindex.html.twig', [
            'oeuvrages' => $oeuvrages,
        ]);
    }

    /**
     * @Route("/new", name="oeuvrage_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $oeuvrage = new Oeuvrage();

        $form = $this->createForm(OeuvrageType::class, $oeuvrage);
        $form->add('image', FileType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->find(User::class, 1);
            $oeuvrage->setUser($user);
            $entityManager->persist($oeuvrage);
            $entityManager->flush();

            $file2 = $form['image']->getData();
            $fileName = $file2->getClientOriginalName();
            $aux = $file2->guessExtension();

            if ($aux == "png" || $aux == "jpeg") {

                try {
                    $file2->move(
                        $this->getParameter('Images_directory'),
                        $fileName);
                    $oeuvrage->setImage($fileName);
                    $entityManager->persist($oeuvrage);
                    $entityManager->flush();

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
            }
            else {
                echo 'Le fichier doit être une Image !';
            }

            return $this->redirectToRoute('oeuvrage_indexvendor');
        }

        return $this->render('oeuvrage/new.html.twig', [
            'oeuvrage' => $oeuvrage,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{oeuvrageId}", name="oeuvrage_show", methods={"GET"})
     */
    public function show(Oeuvrage $oeuvrage): Response
    {
        $favoris = $this->getDoctrine()
            ->getRepository(FavorisO::class)
            ->findOneBy(['oeuvrage'=>$oeuvrage ,'user'=> 1 ]);

        return $this->render('oeuvrage/show.html.twig', [
            'oeuvrage' => $oeuvrage,
            'favoris' => $favoris
        ]);
    }
    /**
     * @Route("/admin/{oeuvrageId}", name="oeuvrage_showadmin", methods={"GET"})
     */
    public function showadmin(Oeuvrage $oeuvrage): Response
    {
        return $this->render('oeuvrage/backadminshow.html.twig', [
            'oeuvrage' => $oeuvrage,
        ]);
    }


    /**
     * @Route("/admin/{oeuvrageId}/valider", name="oeuvrage_valider", methods={"GET","POST"})
     */
    public function valider(Request $request,  Oeuvrage $oeuvrage): Response
    {
        if ($this->isCsrfTokenValid('valider'.$oeuvrage->getOeuvrageId(), $request->request->get('_token'))) {
            $oeuvrage->setIsvalid(1);
            $oeuvrage->getUser()->setIsVendeur(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }
        return $this->redirectToRoute('oeuvrage_indexa');

    }

    /**
     * @Route("/chart/o" , name="admin_chart")
     */
    public function stat(OeuvrageRepository $oeuvrageRepository){

       // $users = $userRepository->findAll();
        $categNom = ["Peinture" , "Artisanat" , "Décoration", "Sculpture" , "Litérature"];
        $categColor = ["#a65959" , "#414e4d" , "#5E8486", "#A0D7A8", "#EFBC9B", "#DAF7A6"];

        $categPeint = count($oeuvrageRepository->findBy(["domaine" =>"Peinture"]) )  ;
        $categArt = count($oeuvrageRepository->findBy(["domaine" =>"Artisanat"]) ) ;
        $categDec = count($oeuvrageRepository->findBy(["domaine" => "Décoration"]) ) ;
        $categScul = count($oeuvrageRepository->findBy(["domaine" =>"Sculpture"]) ) ;
        $categLit = count($oeuvrageRepository->findBy(["domaine" => "Litérature"]) ) ;
        $categCount = [ $categPeint , $categArt , $categDec , $categScul ,$categLit ];

        //////

      //  $categColor = ["#a65959" , "#414e4d" , "#343e3d", ];

        $oeuvrages =$this->getDoctrine()
                 ->getRepository(Oeuvrage::class)
                 ->countvendor();
        //dd($oeuvrages);
        $listvend = array();
        $listvendCount = array();
       // foreach ( $oeuvrages AS $o) {
         //   $listvend = $o->getUser()->getUsername();
           // $listvendCount = count($oeuvrageRepository->findBy(["user" =>$o->getUser()->getUserId()]) );
            //           }
        for ($i =0 ; $i < count($oeuvrages) ; ++$i) {
            $listvend[$i]  =   $oeuvrages[$i]->getUser()->getUserName() ;
            $listvendCount[$i]  =   count($oeuvrageRepository->findBy(["user" => $oeuvrages[$i]->getUser()->getUserId()]) );

        }

        return $this->render('oeuvrage/Chartoeuvre.html.twig', [
                'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            'listvend' => json_encode($listvend),
            'listvendcount' => json_encode($listvendCount),

        ]);
    }

    /**
     * @Route("/admin/{oeuvrageId}/invalider", name="oeuvrage_invalider", methods={"GET","POST"})
     */
    public function invalider(Request $request,  Oeuvrage $oeuvrage): Response
    {
        if ($this->isCsrfTokenValid('valider'.$oeuvrage->getOeuvrageId(), $request->request->get('_token'))) {
            $oeuvrage->setIsvalid(2);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }
        return $this->redirectToRoute('oeuvrage_indexa');

    }


    /**
     * @Route("/vendor/{oeuvrageId}", name="oeuvrage_showvendor", methods={"GET"})
     */
    public function showvendor(Oeuvrage $oeuvrage): Response
    {
        return $this->render('oeuvrage/showforvendor.html.twig', [
            'oeuvrage' => $oeuvrage,
        ]);
    }

    /**
     * @Route("/{oeuvrageId}/edit", name="oeuvrage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,  $oeuvrageId): Response
    {
        $oeuvrage =$this->getDoctrine()
            ->getRepository(Oeuvrage::class)->find($oeuvrageId);

        $form = $this->createForm(OeuvrageType::class, $oeuvrage);
        //$form->add('image',FileType::class,array('label'=>'inserer une image','data_class' => null));


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var UploadedFile $file
             */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('Images_directory'),$fileName);
            //md5(uniqid()) . '.' . $file2->guessExtension(); //Crypter le nom de l'image
            /*
             * $fileName = $file2->getClientOriginalName();
             *  $aux = $file2->guessExtension();
             */
            $oeuvrage->setImage($fileName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('oeuvrage_indexvendor');
        }

        return $this->render('oeuvrage/edit.html.twig', [
            'oeuvrage' => $oeuvrage,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/{oeuvrageId}", name="oeuvrage_delete", methods={"POST"})
     */
    public function delete(Request $request, Oeuvrage $oeuvrage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvrage->getOeuvrageId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($oeuvrage);
            $entityManager->flush();
        }
        return $this->redirectToRoute('oeuvrage_indexvendor');
    }

    /**
     * @Route("/{oeuvrageId}/favoris", name="oeuvrage_favoris",  methods={"GET","POST"})
     */
    public function Favoris (Request $request, Oeuvrage $oeuvrage): Response
    {
        $favoris = $this->getDoctrine()
            ->getRepository(FavorisO::class)
            ->findOneBy(['oeuvrage'=>$oeuvrage ,'user'=> 1 ]);
        if  (!$favoris ){
            $user =  $this->getDoctrine()->getManager()->find(User::class, 1);
            $response = $this->forward('App\Controller\FavorisOController::newf', [
                'user'  => $user,
                'oeuvrage' => $oeuvrage,
            ]);
            return $response;
        }
        else { $response = $this->forward('App\Controller\FavorisOController::delete', [
            'favoris' => $favoris,
            'oeuvrage' => $oeuvrage,
        ]);
            return $response;

        }
    }


/*
 *


     * @Route("/listefavoris", name="oeuvrage_listfavoris", methods={"GET"})

    public function listfavoris( ): Response
    {

        $oeuvrages = $this->getDoctrine()
            ->getRepository(Oeuvrage::class)
            ->findBy(['user'=>1]);

        return $this->render('oeuvrage/affoeuvre_vendor.html.twig', [
            'oeuvrages' => $oeuvrages,

        ]);
    }
*/

}
