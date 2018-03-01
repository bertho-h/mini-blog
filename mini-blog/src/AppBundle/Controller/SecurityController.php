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
