<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
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
        return $this->render('formation/admin.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }
    /**
     * @Route("/", name="formation_index", methods={"GET"})
     */
    /* hedhi methode lel admin zeda w lezem nbadel esm .twig*/
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @param FormationRepository $formationRepository
     * @return Response
     * @Route ("/Valid", name="Valid", methods={"GET","POST"})
     */
    public function findValid(FormationRepository $formationRepository):Response
    {/*$formation = $formationRepository->findByisvalid(1);*/
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findByisvalid(1),
        ]);
       }

    /**
     * @param FormationRepository $formationRepository
     * @return Response
     * @Route ("/nonValid", name="nonValid", methods={"GET","POST"})
     */

    public function findNonvalid(FormationRepository $formationRepository):Response
    {/*$formation = $formationRepository->findByisvalid(1);*/
        return $this->render('formation/admin.html.twig', [
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
            return $this->redirectToRoute('formation_index');
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{formationId}", name="formation_show", methods={"GET"})
     */
    public function show(Formation $formation): Response
    {
        return $this->render('formation/show.html.twig', [
            'formation' => $formation,
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
}
