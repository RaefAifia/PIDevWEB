<?php

namespace App\Controller;
use App\Entity\Formation;
use App\Repository\InscriptionRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @Route("/cours")
 */
class CoursController extends AbstractController
{


    /**
     * @Route("/{id}", name="formation_cours_index", methods={"GET"})
     */
    public function indexCours(Formation $formation,CoursRepository $coursRepository,Request $request, InscriptionRepository $inscriptionRepository ): Response
    {$user = $this->getUser();
        $cours = $coursRepository->findByFor($request);
        $i = $inscriptionRepository->findB($formation, $user);
        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
            'isincrit' => $i,
            'user'=> $user,
        ]);
    }

        /**
         * @Route("/backoffice/{id}", name="formation_cours_Admin", methods={"GET","POST"})
         */
        public function indexCoursAdmin(CoursRepository $coursRepository,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $cours=$coursRepository->findByFor($request);

        return $this->render('Admin/adminCours.html.twig', [
            'cours' =>$cours
        ]);


    }
    /**
     * @Route("/backoffice/show/{id}", name="formation_cours_AdminSH", methods={"GET","POST"})
     */
    public function CoursAdmin(Cours $cour): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        return $this->render('Admin/AdminShCours.html.twig', [
            'cour' =>$cour
        ]);


    }
    /*
    /**
     * @Route("/", name="cours_index", methods={"GET"})
     */
  /*  public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }*/


    /**
     * @Route("/new/{formationId}", name="cours_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $cour = new Cours();
        $form = $this->createForm(CoursType::class, $cour);
        $form->add('image', FileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

          $query = $entityManager->createQuery("SELECT f FROM App\Entity\Formation f WHERE f.formationId = :formationId");
          $query->setParameter('formationId',$request->attributes->get('formationId'));
            $formation = $query->getSingleResult();
            $cour->setFormation($formation);

           $entityManager->persist($cour);
            $entityManager->flush();
            $file2 = $form['image']->getData();
            $fileName = $file2->getClientOriginalName();
            $aux = $file2->guessExtension();


                try {
                    $file2->move(
                        $this->getParameter('Images_directory1'),
                        $fileName);
                    $cour->setImage($fileName);
                    $entityManager->persist($cour);
                    $entityManager->flush();

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
           // dd($formation);
            $this->addFlash('success', 'Cours ajouté ! Vous pouvez associer autres cours à cette formation maintenant !');

            return $this->redirectToRoute('formation_cours_index',array('id' => $formation->getFormationId()));
        }

        return $this->render('cours/new.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/afficher/{coursId}", name="cours_show", methods={"GET","POST"})
     */
    public function show(Cours $cour): Response
    {
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    /**
     * @Route("/{coursId}/edit", name="cours_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cours $cour): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(CoursType::class, $cour);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cours_index');
        }

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{coursId}", name="cours_delete", methods={"POST"})
     */
    public function delete(Request $request, Cours $cour): Response
    {$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->isCsrfTokenValid('delete'.$cour->getCoursId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cours_index');
    }

}
