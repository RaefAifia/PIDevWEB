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
     * @Route("/admin", name="admin")
     */
    public function indexAdmin(): Response
    {
        return $this->render('BackOffice/admin.html.twig', [
            'controller_name' => 'FormationController',
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
     * @Route("/", name="formateur_index", methods={"GET"})
     */

   public function indexFormateur(FormationRepository $formationRepository,Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery("SELECT * FROM App\Entity\Formation f WHERE f.user = :id");
        $query->setParameter('id',$request->attributes->get('id'));
        $UID = $query->getSingleResult();


        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findByuser($UID),

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
           return $this->redirectToRoute('cours_new',array('formationId' => $formation->getFormationId()));
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

        ]);
    }


    /**
     * @Route("/{formationId}/edit", name="formation_edit", methods={"GET","POST"})
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
     * @Route("/backoffice/{formationId}", name="valider_formation", methods={"POST"})
     */
    public function validerF(Request $request, Formation $formation): Response
    {
        if ($this->isCsrfTokenValid('validerF'.$formation->getFormationId(), $request->request->get('_token'))) {
            $formation->setIsvalid(1);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
        }

        return $this->redirectToRoute('nonValid');
    }


}
