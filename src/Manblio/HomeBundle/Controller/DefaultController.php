<?php

namespace Manblio\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur=$em->getRepository('ManblioUserBundle:User')->findOneBy(array(), array('id'=> 'DESC'));
        $dernierTome=$em->getRepository('ManblioLivreBundle:Livre')->findBy(array(), array('id'=> 'DESC'),5);
        $dernierSerie=$em->getRepository('ManblioLivreBundle:NomSerie')->findOneBy(array(), array('id'=> 'DESC'),5);
        $dernierPossession=$em->getRepository('ManblioLivreBundle:Possession')->findBy(array(), array('id'=> 'DESC'),5);
        // Afiche l'index du site
        return $this->render('ManblioHomeBundle:Default:index.html.twig', array(
                'utilisateur' => $utilisateur,
                'serie'=> $dernierSerie,
                'tomes' => $dernierTome,
                'possession' => $dernierPossession,
            ));
    }

    public function getMenuAction()
    {   
        // Affiche le menu
    	return $this->render('ManblioHomeBundle:Default:menu.html.twig', array());
    }

    public function searchAction($keyWord){
        // Appele d'entity manager
    	$em = $this->getDoctrine()->getManager();
    	
        //Recupere les utilisateur correspondant aux mots clé
    	$users=$em->getRepository('ManblioUserBundle:User')->SearchUser($keyWord);
    	
        // Récuper les livre correspondant aux mots clé 
    	$livres=$em->getRepository('ManblioLivreBundle:NomSerie')->SearchSerie($keyWord);
    	
        // Affiche la vue 
    	return $this->container->get('templating')->renderResponse('ManblioHomeBundle:Default:resultat.html.twig', array(
            'users' => $users,
            'livres' => $livres,
            ));
    	
    }
}
