<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use AppBundle\Entity\Commentaire;
use AppBundle\Form\CommentaireType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends Controller {

  /**
  *@Route("/articles", name="article.index")
  *@Method({"GET", "POST"})
  */
  public function indexAction(Request $request) {
    $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
    $articles = $repository->findAll();
    $article = new Article();
    $form = $this->createForm(ArticleType::class, $article);

    $form->add('Create', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($article);
      $entityManager->flush();
      return $this->redirectToRoute('article.index');
    }
    return $this->render('articles/index.html.twig', [
      'articles' => $articles,
      'form' => $form->createView()
    ]);
  }

  /**
  *@Route("/articles/view/{id}", name="article.view")
  *@Method({"GET", "POST"})
  */
  public function viewAction(Request $request, Article $article) {
    $repository = $this->getDoctrine()->getRepository('AppBundle:Commentaire');
    $commentaires = $repository->findAllWithArticle($article);

    $commentaire = new Commentaire();
    $form = $this->createForm(CommentaireType::class, $commentaire);
    $form->add('Add', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager = $this->getDoctrine()->getManager();
      $commentaire->setArticle($article);
      $entityManager->persist($commentaire);
      $entityManager->flush();
      return $this->redirectToRoute('article.view', array('id' => $article->getId()));
    }
    return $this->render('articles/view.html.twig', [
      'commentaires' => $commentaires,
      'article' => $article,
      'form' => $form->createView()
    ]);
  }


  /**
  *@Route("/articles/{id}", name="article.edit")
  *@Method({"GET", "POST"})
  */
  public function editAction(Request $request, Article $article) {
    $form = $this->createForm(ArticleType::class, $article);
    $form->add('Edit', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->getDoctrine()->getManager()->flush();
      return $this->redirectToRoute('article.index');
    }

    return $this->render('articles/edit.html.twig', [
      'form' => $form->createView(),
      'article' => $article,
    ]);
  }

  /**
  *@Route("/articles/delete/{id}", name="article.delete")
  *@Method({"GET","POST"})
  */
  public function deleteAction(Request $request, Article $article) {
    $entityManager = $this->getDoctrine()->getManager();
    $commentaires = $article->getCommentaires();
    foreach ($commentaires as $commentaire) {
      $entityManager->remove($commentaire);
    }
    $entityManager->remove($article);
    $entityManager->flush();
    return $this->redirectToRoute('article.index');
  }

}

?>
