<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


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
}

 ?>
