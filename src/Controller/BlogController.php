<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\CommentBlog;
use App\Form\BlogType;
use App\Form\CommentBlogType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(): Response
    {
        $blogs = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAll();

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }
    /**
     * @Route("/front", name="blog_index_front", methods={"GET"})
     */
    public function indexFront(): Response
    {
        $blogs = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAll();

        return $this->render('blog/front.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/new", name="blog_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$blog->getImageFile();
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $blog->setImage($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{blogId}", name="blog_show", methods={"GET"})
     */
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/{blogId}/edit", name="blog_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blog $blog): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$blog->getImageFile();
            if($file)
            {
            $filename=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $filename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $blog->setImage($filename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
 * @Route("/delete/{blogId}", name="blog_delete")
 */
    public function delete(Request $request, Blog $blog): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($blog);
        $entityManager->flush();


        return $this->redirectToRoute('blog_index');
    }
    /**
     * @Route("/detail/{blogId}", name="blog_detail")
     */
    public function BlogDetail(Request $request, Blog $blog): Response
    {

        $comment = new CommentBlog();
        $form = $this->createForm(CommentBlogType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $comment->setDate(new \DateTime());
           $comment->setBlog($blog);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirect('/blog/detail/'.$blog->getBlogId());
        }


        return $this->render('blog/detail.html.twig', [
            'blog' => $blog,
            'form'=>$form->createView()
        ]);
    }
}
