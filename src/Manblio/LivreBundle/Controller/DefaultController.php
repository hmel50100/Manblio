<?php

namespace Manblio\LivreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function afficheSerieAction($page){
        $em = $this->getDoctrine()->getManager();
        $maxArticles = 10;
        $articles_count = count($em->getRepository('ManblioLivreBundle:NomSerie')->findBy(
            array(),
            array("nomSerie"=>'asc')
        ));
        $pagination = array(
            'page' => $page,
            'route' => 'touteSerie',
            'pages_count' => ceil($articles_count / $maxArticles),
            'route_params' => array()
        );
 
         $series = $this->getDoctrine()->getRepository('ManblioLivreBundle:NomSerie')
                ->getList($page, $maxArticles);
 
        return $this->render('ManblioLivreBundle:Default:touteSerie.html.twig', array(
            'series' => $series,
            'pagination' => $pagination
        ));
/*    	// Recupere toutes les series dans la BDD
        $em = $this->getDoctrine()->getManager();
    	$series = $em->getRepository('ManblioLivreBundle:NomSerie')->findBy(
            array(),
            array("nomSerie"=>'asc')
        );
        
        // affiche la vue 
        return $this->render('ManblioLivreBundle:Default:touteSerie.html.twig', 
            array(
                'series' => $series,
                )
        );*/
    }

    public function detailSerieAction($id){
    	//recupere les info de la serie
        $em = $this->getDoctrine()->getManager();
        $serie=$em->getRepository('ManblioLivreBundle:NomSerie')->findOneById($id);
    	
        // recupere tous les livre de la serie
        $livres=$em->getRepository('ManblioLivreBundle:Livre')->findBy(
            array("nomSerie" => $id),
            array("id" => 'desc')
            );
        
        //si l'utilisateur et connecte
        if ($this->get('security.context')->isGranted('ROLE_USER')){
            $id=$this->get('security.context')->getToken()->getUser()->getId();
            $livreByUser=$em->getRepository('ManblioLivreBundle:Possession')->findByUtilisateur($id);
            
            //affiche la vue avec les boutton ajouter et supprimer possession    
            return $this->render('ManblioLivreBundle:Default:connecteLivres.html.twig', 
                array(
                    'livres'=> $livres,
                    'livreByUser' => $livreByUser,
                    'serie'=> $serie,
                )
            );
        }
        else{
            //affiche la vue sans les bouttons ajouter et supprimer possession
            return $this->render('ManblioLivreBundle:Default:livres.html.twig', 
                array(
                    'livres'=> $livres,
                    'serie'=> $serie,
                )
            );
        }   	
    }

    public function afficheCollectionAction($id){
        //recupere les info de la serie
        $em = $this->getDoctrine()->getManager();
        $series=$em->getRepository('ManblioLivreBundle:NomSerie')->findMyBook($id);
        
        $i=0;

        foreach($series as $s){
            $livre=$em->getRepository('ManblioLivreBundle:Livre')->findByNomSerie($s->getId());
            $serie[$i]=array("livre"=>$s, "max" =>count($livre));
            $i++;
        }
        $livreByUser=$em->getRepository('ManblioLivreBundle:Possession')->findByUtilisateur($id);
            
        //Affiche la collection de l'utilisateur
            return $this->render('ManblioLivreBundle:Default:showCollection.html.twig', 
                array(
                    'series'=> $serie,
                    'livreByUser' => $livreByUser,
                )
            );
    }

}

