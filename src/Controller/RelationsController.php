<?php

namespace App\Controller;

use App\Entity\Relations;
use App\Entity\User;
use App\Form\RelationsType;
use App\Repository\RelationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/relations")
 */
class RelationsController extends AbstractController
{
    /**
     * @Route("/", name="relations_index", methods={"GET"})
     */
    public function index(RelationsRepository $relationsRepository): Response
    {
        return $this->render('relations/index.html.twig', [
            'relations' => $relationsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="relations_new", methods={"GET","POST"})
     */
    public function newR( $user): Response
    {
        $relation = new Relations();

        $id=$user->getUserId();
        echo($id);
        $relation->setFollower( $this->getUser());
        $relation->setFollowee($user);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($relation);
        $entityManager->flush();

        return $this->redirectToRoute('user_show', [
            'userId' => $user->getUserId(),
        ]);
    }

    /**
     * @Route("/{follower}", name="relations_show", methods={"GET"})
     */
    public function show(Relations $relation): Response
    {
        return $this->render('relations/show.html.twig', [
            'relation' => $relation,
        ]);
    }

    /**
     * @Route("/{follower}/edit", name="relations_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Relations $relation): Response
    {
        $form = $this->createForm(RelationsType::class, $relation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('relations_index');
        }

        return $this->render('relations/edit.html.twig', [
            'relation' => $relation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{follower}", name="relations_delete", methods={"POST"})
     */
    public function delete(Request $request, Relations $relation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$relation->getFollower(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($relation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('relations_index');
    }
    /**
     * @Route("/delete", name="relations_delete", methods={"POST"})
     */
    public function deleteR( $relation, $user): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($relation);
        $entityManager->flush();
        return $this->redirectToRoute('user_show', [
            'userId' => $user->getUserId(),

        ]);
    }
}
