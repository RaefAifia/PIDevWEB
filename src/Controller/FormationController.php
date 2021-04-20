<?php

namespace App\Controller;
use App\Form\SearchForm;
use App\Data\SearchData;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Form\FormationType;
use App\Form\InscriptionType;
use App\Entity\User;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{

    /**
     * @param FormationRepository $repository
     * @return Response
     * @Route ("/backoffice/charts",name="charts",methods={"POST","GET"})
     */

    public function stat(FormationRepository $repository){
        $debutant = 0;
        $inter = 0;
        $avance = 0;
//
       // $formation = $repository->findAll();
     //   $categNom = ["Débutant", "intermédiaire", "avancé"];
        $categNom = ["danse" , "theatre" , "musique", "peinture" , "littérature","audiovisuel","sculpture"];
        $categColor = ["#a65959" , "#414e4d" , "#343e3d","#A0D7A8", "#EFBC9B", "#DAF7A6", "#a00000","#EFBC9B","#EFBC9B"];

        $categDanse = count($repository->findBy(["domaine" =>"danse"]) )  ;
          $categTheatre = count($repository->findBy(["domaine" =>"theatre"]) ) ;
          $categMusique = count($repository->findBy(["domaine" => "musique"]) ) ;
        $categPeint = count($repository->findBy(["domaine" =>"peinture"]) )  ;
        $categLit = count($repository->findBy(["domaine" =>"littérature"]) ) ;
        $categA = count($repository->findBy(["domaine" => "audiovisuel"]) ) ;
        $categScul = count($repository->findBy(["domaine" =>"sculpture"]) ) ;

          $categCount = [$categDanse , $categTheatre , $categMusique,$categPeint,$categLit,$categA,$categScul ];


//        foreach ($formation as $indicateur) {
//            if($indicateur->getNiveau()=='Débutant'){
//                $debutant=$debutant+1;
//            }
//            elseif ($indicateur->getNiveau()=='avancé'){
//                $avance=$avance+1;
//            }
//            else {$inter=$inter+1;}
//        }
//        $nivCount = [$debutant , $inter , $avance ];
        // dd($debutant);
        return $this->render('BackOffice/charts.html.twig',[

            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            //'nivCount' => json_encode($nivCount),
            'categCount' => json_encode($categCount),


        ]);
    }

//    /**
// * @param Request $request
// * @return Response
// * @Route ("/backoffice/charts",name="charts",methods={"POST","GET"})
// */
//    public function charts (Request $request){
//        $debutant = 0;
//        $inter = 0;
//        $avance = 0;
//
//        $em = $this->getDoctrine()->getManager();
//        $formation = $em->getRepository(Formation::class)->findAll();
//        foreach ($formation as $indicateur) {
//            if($indicateur->getNiveau()=='Débutant'){
//                $debutant=$debutant+1;
//            }
//            elseif ($indicateur->getNiveau()=='avancé'){
//                $avance=$avance+1;
//            }
//            else {$inter=$inter+1;}
//        }
//     //   dd($debutant);
//        return $this->render('BackOffice/charts.html.twig',[
//                'Debutant' => $debutant,
//                'inter'=> $inter,
//                'avance'=> $avance
//
//            ]
//        );
//
////
//    }

    /**
     * @Route("/admin", name="admin")
     */
    public function indexAdmin(FormationRepository $formationRepository): Response
    {
        return $this->render('BackOffice/admin.html.twig', [

            'formations' => $formationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/", name="formation_index")
     */
    public function indexRecherche(FormationRepository $repository, Request $request)
    {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        //dd($data);
     $formations = $repository->findSearch($data);
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
         // 'formations' => $repository->findAll(),
            'form' => $form->createView()
        ]);
    }
    /*
    /**
     * @Route("/", name="formation_index", methods={"GET"})
     */
    /* hedhi methode lel admin zeda w lezem nbadel esm .twig*/
   /* public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),

        ]);
    }*/

    /**
     * @param FormationRepository $formationRepository
     * @return Response
     * @Route ("/backoffice/Valid", name="Valid", methods={"GET","POST"})
     */
    public function findValid(FormationRepository $formationRepository):Response
    {/*$formation = $formationRepository->findByisvalid(1);*/
        return $this->render('BackOffice/admin.html.twig', [
            'formations' => $formationRepository->findByisvalid(1),
        ]);
       }

    /**
     * @param FormationRepository $formationRepository
     * @return Response
     * @Route ("/backoffice/nonValid", name="nonValid", methods={"GET","POST"})
     */

    public function findNonvalid(FormationRepository $formationRepository):Response
    {/*$formation = $formationRepository->findByisvalid(1);*/
        return $this->render('BackOffice/admin.html.twig', [
            'formations' => $formationRepository->findByisvalid(0),
        ]);
    }

    /**
     * @Route("/formateur/{user}", name="formateur_index", methods={"GET"})
     */

   public function indexFormateur(FormationRepository $formationRepository,Request $request,$user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
      /*  $query = $entityManager->createQuery("SELECT f FROM App\Entity\Formation f WHERE f.user = :id");
        $query->setParameter('id',$request->attributes->get('id'));
        $UID = $query->getSingleResult();*/


        return $this->render('formation/showFormateur.html.twig', [
          //  'formations' => $formationRepository->findByuser($UID),
            'formations' => $formationRepository->findBy(['user'=>$user]),
            'formations' => $formationRepository->findByisvalid(0),
        ]);
    }
    /**
     * @Route("/new", name="formation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->add('image', FileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->find(User::class, 1);
            $formation->setUser($user);


            $entityManager->persist($formation);
            $entityManager->flush();

            $file2 = $form['image']->getData();
            $fileName = $file2->getClientOriginalName();
            $aux = $file2->guessExtension();
            try {
                $file2->move(
                    $this->getParameter('Images_directory'),
                    $fileName);
                $formation->setImage($fileName);
                $entityManager->persist($formation);
                $entityManager->flush();

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $this->addFlash('success', 'Formation ajoutée ! Vous pourvez associer des cours à cette formation maintenant !');
            if ($this->isCsrfTokenValid('Enregistrer'.$formation->getFormationId(), $request->request->get('_token'))) {

           return $this->redirectToRoute('cours_new',array('formationId' => $formation->getFormationId()));}
            else{return $this->redirectToRoute('formateur_index',array('user' => $user->getUserId()));}
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/backoffice/{id}", name="formation_showAdmin", methods={"GET"})
     */
   public function showForAdmin(Formation $formation): Response
    {
        return $this->render('BackOffice/AdminShowFor.html.twig', [
            'formation' => $formation
        ]);
    }
    /**
     * @Route("/{formationId}", name="formation_show", methods={"GET"})
     */
    public function show(Formation $formation): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'inscription'=>$inscription,

        ]);
    }


    /**
     * @Route("/formateur/{formationId}/edit", name="formation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Formation $formation): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('Images_directory'),$fileName);
            md5(uniqid()) . '.' . $file->guessExtension(); //Crypter le nom de l'image

            $formation->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{formationId}", name="formation_delete", methods={"POST"})
     */
    public function delete(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getFormationId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_index');
    }
    /**
     * @Route("/{formationId}", name="formation_delete", methods={"POST"})
     */
    public function invaliderF(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('Invalider'.$formation->getFormationId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('nonValid');
    }
    /**
     * @Route("/backoffice/all",name="formationRecherche")
     */

    public function Recherche(Request $request)
    {
        $search =$request->query->get('formation');
        $em = $this->getDoctrine()->getManager();
        $formation=$em->getRepository(Formation::class)->findTitre($search);


        return $this->render('BackOffice/admin.html.twig',[
                'formation' => $formation]
        );
    }
    /**
     * @Route("/backoffice/{formationId}/valider", name="v_formation", methods={"POST","GET"})
     */
    public function validerF(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('validerF'.$formation->getFormationId(), $request->request->get('_token'))) {
            $formation->setIsvalid(1);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
        }
       // dd($formation);
        return $this->redirectToRoute('Valid');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/backoffice/nonValid/tri",name="tri", methods={"POST","GET"})
     */
    public function tridDate(Request $request)
    {
       // $search =$request->query->get('formation');
        $em = $this->getDoctrine()->getManager();
        $formation=$em->getRepository(Formation::class)->tri();

    //dd($formation);
        return $this->render('BackOffice/admin.html.twig',[
                'formations' => $formation]
        );
    }



    }


