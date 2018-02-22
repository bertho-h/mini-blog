<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
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
  return $this->render('articles/view.html.twig', [
    'article' => $article
  ]);
}

}

?>
