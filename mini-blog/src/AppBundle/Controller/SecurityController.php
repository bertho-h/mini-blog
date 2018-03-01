<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SecurityController extends Controller {


  /**
  *@Route("/login", name="login")
  *
  */
  public function loginAction() {
    //Code that generates administrator user
      // $user = new User;
      // $em = $this->getDoctrine()->getManager();
      // $user->setUsername('admin');
      // $encoder = $this->get('security.password_encoder');
      // $user->setPassword($encoder->encodePassword($user, 'admin'));
      // $em->persist($user);
      // $em->flush();

    $utils = $this->get('security.authentication_utils');
    $error = $utils->getLastAuthenticationError();
    $lastUsername = $utils->getLastUserName();
    return $this->render('security/login.html.twig', [
      'username' => $lastUsername,
      'error'=> $error
    ]);
  }

  /**
  *@Route("/signup", name="signup")
  *
  */
  public function signupAction(Request $request) {
    $user = new User;
    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(UserType::class, $user);
    $encoder = $this->get('security.password_encoder');

    $form->add('Create', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setUsername($form['username']->getData());
      $password = $encoder->encodePassword($user, $form['username']->getData());
      $user->setPassword($password);
      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('login');
    }
    return $this->render('security/signup.html.twig', [
      'form' => $form->createView()
    ]);

  }
}

 ?>
