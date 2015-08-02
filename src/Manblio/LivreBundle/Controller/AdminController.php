<?php

namespace Manblio\LivreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Manblio\LivreBundle\Form\NomSerieType;
use Manblio\LivreBundle\Entity\NomSerie;
use Manblio\LivreBundle\Form\LivreType;
use Manblio\LivreBundle\Entity\Livre;
use Manblio\LivreBundle\Form\PossessionType;
use Manblio\LivreBundle\Entity\Possession;
use Manblio\LivreBundle\Entity\NomSerieRepository;

class AdminController extends Controller{
	
	// Gestion de la bibliotheque
	public function adminAction(){
		
		//recupere les livre dans la base de donnee
		$em = $this->getDoctrine()->getManager();
		$livres = $em->getRepository('ManblioLivreBundle:NomSerie')->findAllLivreAndSerieTrier();

		//affichage de la vue 
		return $this->render('ManblioLivreBundle:Admin:admin.html.twig', 
			array(
					'livres' => $livres,
				)
		);
	}

    public function ajouterSerieAction(){
    	// Appele de Entity Manager
		$em = $this->getDoctrine()->getManager();

		// Instensie un nouveau livre 
		$newNomSerie = new NomSerie();
		
		$form = $this->createForm(new NomSerieType(), $newNomSerie);
		//Recupere les series pour les mettre dans le menu select
			$request=$this->getRequest();
        	if ('POST' === $request->getMethod()){
        		// Recupere les donnée du formulaire 
        		$nomSerie=$this->getRequest()->request->get('nomSerie');
	         	$auteur=$this->getRequest()->request->get('auteur');
	         	$description=$this->getRequest()->request->get('description');
					

					// Attribe les données
	         		$form->bind($request);
					$newNomSerie=$form->getData();
					$newNomSerie->upload();
					
					$newNomSerie->setNomSerie($nomSerie);
					$newNomSerie->setAuteur($auteur);
					$newNomSerie->setDescription($description);
					$newNomSerie->setIsFinish(0);
					
					// Persiste et envoie en BDD
					$em->persist($newNomSerie);
					$em->flush();

	        	// Redirige vers la page detail de la serie 
	        	return $this->redirect($this->generateUrl('gestionBibliotheque'));
	        
    	}
    	

    	// Affiche le formulaire 
        return $this->render("ManblioLivreBundle:Form:ajouterSerie.html.twig", array(
        		'form' => $form->createView(),
        	));
    	
/*

    	// on cree un objet nomserie et un formulaire
    	$em = $this->getDoctrine()->getManager();	
		$b = new NomSerie();
		$form = $this->createForm(new NomSerieType(), $b);		
		
		//on recupere la requete
		$request= $this->getRequest();

		// si le formulaire a ete soumis on envoie les donnee dans la base
		if($request->isMethod("POST")){
			$form->bind($request);
			$b=$form->getData();
			$b->upload();
			$em->persist($b);
			$em->flush();

			// on redirige vers la page d'administration
			return $this->redirect($this->generateUrl("gestionBibliotheque"));
		}

		// on affiche la vue avec le formulaire
		return $this->render("ManblioLivreBundle:Form:ajouterSerie.html.twig", array(
				'form' => $form->createView(),
			));*/
	}

	public function ajouterLivreAction(){
            
                $curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
		//on cree un objet livre est un formulaire
		$em = $this->getDoctrine()->getManager();
                $user=$em->getRepository('ManblioUserBundle:User')->findOneById($curentUserId);
		$b = new Livre();
		$form = $this->createForm(new LivreType(), $b);		
		
		// on recupere la requete
		$request= $this->getRequest();

		// si le formulaire a ete soumis, on envoie les donnees dans la BDD
		if($request->isMethod("POST")){
			$form->bind($request);
			$b=$form->getData();
                        $b->setQuiCree($user);
			$em->persist($b);
			$em->flush();

			// on redirige vers la page d'administration
			return $this->redirect($this->generateUrl("gestionBibliotheque"));
		}

		// on affiche la vue avec le formulaire
		return $this->render("ManblioLivreBundle:Form:ajouterLivre.html.twig", array(
				'form' => $form->createView(),
			));
	}

	public function ajouterPossessionAction($id,$idSerie){
		$em = $this->getDoctrine()->getManager();
		
		// on recupere l'objet livre correspondantà l'id 
		$livre=$em->getRepository('ManblioLivreBundle:Livre')->findOneById($id);
		
		// on recupere l'objet utilisateur correspondant a l'utilisateur connecte
		$curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
		$user =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
		
		// on cree un objet possession, et on lui affecte le livre et l'utilisateur
				
		$possession = new Possession();
	    $possession->setLivre($livre);
	    $possession->setUtilisateur($user);
	    
	    // et on envoie les donnée dans la BDD
	    $em->persist($possession);
	    $em->flush();

	    // on redirige vers la page de la serie
	    return $this->redirect($this->generateUrl('detailSerie', array('id' => $idSerie))."#".$id);
	}

	public function supprimerPossessionAction($id,$idSerie,$idTome){
		//on récupere la possession dans la bdd
		$em = $this->getDoctrine()->getManager();
		$possession=$em->getRepository('ManblioLivreBundle:Possession')->findOneById($id);

		// on la supprime de la BDD
	    $em->remove($possession);
	    $em->flush();

	    // on redirige vers la page de la serie
	    return $this->redirect($this->generateUrl('detailSerie', array('id' => $idSerie))."#".$idTome);
	}

	public function supprimerLivreAction($id,$idSerie){
		// on recupere le livre avec son id
		$em = $this->getDoctrine()->getManager();
		$livre= $em->getRepository('ManblioLivreBundle:Livre')->find($id);
		
		// on recupere les possession par livre
		$possession=$em->getRepository('ManblioLivreBundle:Possession')->findByLivre($id);
		
		// si il n'y a pas de possession on peu le supprimer
		if(empty($possession)){
			$em->remove($livre);
			$em->flush();
			// redirige vers la gestion de la bibliotheque
			return $this->redirect($this->generateUrl("gestionBibliotheque")."?id=".$idSerie);
		}
		else{
			// si non on redirige vers la bibliotheque avec une erreure
			return $this->redirect($this->generateUrl("gestionBibliotheque", array('error'=> 1)));
		}
	}

	public function modifierLivreAction($id){
		$em = $this->getDoctrine()->getManager();

		// on recupere un livre avec son id
		$b=$em->getRepository('ManblioLivreBundle:Livre')->findOneById($id);

		// on cree un formulaire hydrate avec les donnees
		$form = $this->createForm(new LivreType(), $b);

		//On recupere la requete
		$request= $this->getRequest();

		// si le formulaire a ete soumis on envoie les nouvelle infos dans la BDD
		if($request->isMethod("POST")){
			$form->bind($request);
			$b=$form->getData();

			$em->persist($b);
			$em->flush();

			// on redirige vers la page d'administration
			return $this->redirect($this->generateUrl("gestionBibliotheque"));
		}
		// on affiche le fomrulaire 
		return $this->render("ManblioLivreBundle:Form:modifierLivre.html.twig", array(
				'form' => $form->createView(),
				'id' => $id,
			));
	}

	public function createAllSerieAction(){
		// Appele de Entity Manager
		$em = $this->getDoctrine()->getManager();
		//Recupere les series pour les mettre dans le menu select
		$series=$em->getRepository('ManblioLivreBundle:NomSerie')->findAll(array(),array('nomSerie'=> 'ASC'));

		// Recupere l'id de l'utilisateur en cour
		$curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
		$user =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);

			// verifie qu'il n'y a pas eu de requete
			$request=$this->getRequest();
        	if ('POST' === $request->getMethod()){
        		// Recupere les donnée du formulaire 
        		$nbre=$this->getRequest()->request->get('nbre');
	         	$serieId=$this->getRequest()->request->get('serie');
	         	$prix=$this->getRequest()->request->get('prix');
	         	
	         	// Recupere la serie qui a été choisie
	         	$serie=$em->getRepository('ManblioLivreBundle:NomSerie')->find($serieId);
	         	
	         	// Récupere le dernier tome ajouter de la serie ci-dessus
	         	$lastLivreId=$em->getRepository('ManblioLivreBundle:Livre')->findOneBy(array("nomSerie"=>$serie->getId()),array("numero"=>"desc"));
	         	
	         	// Si il y a deja des livre dans la serie on part du dernier tome +1
	         	if($lastLivreId){
	         	$firstId=$lastLivreId->getNumero()+1;
	         	}
	         	// Si non on commance a 1
	         	else{
	         		$firstId=1;
	         	}
	         	// Nbre de tome a ajouter
	         	$aAjouter=$nbre;

	         	// Numero des t-uples
	         	$i=$firstId;

	         	// Execute autant de fois que de numero a ajouter
				do{
					// Instensie un nouveau livre 
					$newLivre = new Livre();
					// Attribe les données
					$newLivre->setPrix($prix);
					$newLivre->setNumero($i);
					$newLivre->setNomSerie($serie);
					$newLivre->setQuiCree($user);
					
					// Persiste et envoie en BDD
					$em->persist($newLivre);
					$em->flush();
					// Incremente pour le le t-uple prochain soit a n+1
					$i++;
				}while($i<$firstId+$aAjouter);
	        	
	        	// Redirige vers la page detail de la serie 
	        	return $this->redirect($this->generateUrl('detailSerie', array('id' => $serie->getId())));
	        
    	}
    	

    	// Affiche le formulaire 
        return $this->render("ManblioLivreBundle:Form:addAllLivre.html.twig", array(
				'series' => $series,
			));
    	
		
	}

	public function maCollectionAction(){
        //recupere les info de la serie
        $em = $this->getDoctrine()->getManager();
         $id=$this->get('security.context')->getToken()->getUser()->getId();
        $livreByUser=$em->getRepository('ManblioLivreBundle:Possession')->findByUtilisateur($id);
        $series=$em->getRepository('ManblioLivreBundle:NomSerie')->findMyBook($id);
        
  		$i=0;

        foreach($series as $s){
            $livre=$em->getRepository('ManblioLivreBundle:Livre')->findByNomSerie($s->getId());
            $serie[$i]=array("livre"=>$s, "max" =>count($livre));
            $i++;
        }
       
            
        //Affiche la collection de l'utilisateur
            return $this->render('ManblioLivreBundle:Admin:maCollection.html.twig', 
                array(
                    'series'=> $serie,
                    'livreByUser' => $livreByUser,
                )
            );
    }

    public function afficherDernierNumeroAction(){
    	// Appele Entity Manager
    	$em = $this->getDoctrine()->getManager();

		$id=$this->get('security.context')->getToken()->getUser()->getId();
    	
    	$serie = $em->getRepository('ManblioLivreBundle:Livre')->getSerieUser($id);

    	$tomes=array();
    	$i=0;
    	foreach($serie as $s){
    		$lastTome = $em->getRepository('ManblioLivreBundle:Livre')->findLastAdd($id, $s->getNomSerie());
    		$thisSerie=$em->getRepository('ManblioLivreBundle:NomSerie')->findOneById($s->getNomSerie());
    		$tomes[$i]=array('numero'=>$lastTome, 'serie' => $thisSerie->getNomSerie());
    		$i++;
    	}
    	
    	//Affiche la vue
    	return $this->render('ManblioLivreBundle:Admin:lastAjout.html.twig',
    		array(
    			'series'=> $tomes,
   			)
    	);
	}

	public function ajouterPlusieursPossessionAction(){
		// Appele de Entity Manager
		$em = $this->getDoctrine()->getManager();
	
		// Recupere l'id de l'utilisateur en cour
		$curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
		$user =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
		$userId=$user->getId();
			// verifie qu'il n'y a pas eu de requete
			$request=$this->getRequest();
        	if ('POST' === $request->getMethod()){
        		// Recupere les donnée du formulaire 
        		$du=$this->getRequest()->request->get('du');
	         	$au=$this->getRequest()->request->get('au');
	         	$serie=$this->getRequest()->request->get('serie');
	         	
	         	$firstLivre= $du;
	         	// Nbre de tome a ajouter
	         	$aAjouter=$au;

	         	// Numero des t-uples
	         	$i=$firstLivre;

	         	// Execute autant de fois que de numero a ajouter
				$var=array();
				do{
					$series=$em->getRepository('ManblioLivreBundle:NomSerie')->find($serie);
					$livre=$em->getRepository('ManblioLivreBundle:Livre')->findByNumeroAndSerie( $i, $series);
					foreach($livre as $l){
						$exist=$em->getRepository('ManblioLivreBundle:Possession')->findUserAndLivre($userId,$l->getId());
						if($exist == null){
							$possession = new Possession();
							$possession->setLivre($l);
							$possession->setUtilisateur($user);
							$em->persist($possession);
							$em->flush();
							$i++;
						}
						else{
							// fait rien
							$i++;
						}
					}

				}while($i<=$aAjouter);

	        	// Redirige vers la page detail de la serie 
	        	return $this->redirect($this->generateUrl('detailSerie', array('id' =>$serie)));
	        
    	}
	}

	public function ajouterTousPossessionAction($serie){
		// Appele de Entity Manager
		$em = $this->getDoctrine()->getManager();
	
		// Recupere l'id de l'utilisateur en cour
		$curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
		$user =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
		$userId=$user->getId();
					$series=$em->getRepository('ManblioLivreBundle:NomSerie')->find($serie);
					$livre=$em->getRepository('ManblioLivreBundle:Livre')->findByNomSerie($series);
					foreach($livre as $l){
						$exist=$em->getRepository('ManblioLivreBundle:Possession')->findUserAndLivre($userId,$l->getId());
						if($exist == null){
							$possession = new Possession();
							$possession->setLivre($l);
							$possession->setUtilisateur($user);
							$em->persist($possession);
							$em->flush();
						}
						else{
							// fait rien
						}
					}
	        	// Redirige vers la page detail de la serie 
	        	return $this->redirect($this->generateUrl('detailSerie', array('id' =>$serie)));
	        
    	}

    	public function setFinishAction($id){
    		$em = $this->getDoctrine()->getManager();
    		$serie=$em->getRepository('ManblioLivreBundle:NomSerie')->findOneById($id);

    		$serie->setIsFinish(1);
    		$em->flush();
    		return $this->redirect($this->generateUrl('touteSerie'));

    	}
	
    	public function setNotFinishAction($id){
    		$em = $this->getDoctrine()->getManager();
    		$serie=$em->getRepository('ManblioLivreBundle:NomSerie')->findOneById($id);

    		$serie->setIsFinish(0);
    		$em->flush();

    		return $this->redirect($this->generateUrl('touteSerie'));
    		
    	}

}
