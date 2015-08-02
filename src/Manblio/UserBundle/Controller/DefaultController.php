<?php

namespace Manblio\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function afficheProfilAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);

        return $this->render('ManblioUserBundle:Default:profil.html.twig', array('user' => $user));
    }
}
