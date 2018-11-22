<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use AppBundle\Entity\Article;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        // return new Response('<html><body>Hello Crud</body></html>');
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticleController.php',
        ]);
    }

    /**
     * @Route("/create-article", name="create_article")
     */
    public function createAction(Request $request){

        $article = new Article();
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('body', TextareaType::class)
            ->add('url', TextType::class,
            array('required' => false, 'attr' => array('placeholder' => 'www.example.com')))
            ->add('save', SubmitType::class, array('label' => 'New Article'))
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirect('/view-article' . $article->getId());
        }

        return $this->render(
            'article/edit.html.twig',
            array('form'=> $form->createView())
        );
    }
}
