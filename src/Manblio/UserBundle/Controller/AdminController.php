<?php

namespace Manblio\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Manblio\UserBundle\Entity\User;
use Manblio\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function changerRoleAction($role, $id){
        if($id!=1){
            switch($role){
                case 'Admin':
                    $this->setAdminAction($id);
                    break;
                case 'Lecteur':
                    $this->setLecteurAction($id);
                    break;

                default:
                    $this->setUserAction($id);
            }
            return $this->redirect($this->generateUrl('manblioAdminUsers', array()));
        }
        else{
            die("Ce profil n'es pas modifiable");
        }
        
        
    }

    public function setAdminAction($id){
        // Appelle entity manager
    	$em = $this->getDoctrine()->getManager();
            // Va chercher l'utilisateur avec son id
        	$user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);
            
            // Attribut le role admin
        	$user->setRoles(array('ROLE_ADMIN'));
            
            // Envoie en base de données
        	$em->flush($user);
       
    }
    public function setLecteurAction($id){
        //Appelle entity manager
    	$em = $this->getDoctrine()->getManager();
        
        // Va chercher l'utilisateur avec son id
    	$user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);

        // Attribut le role lecteur
    	$user->setRoles(array('ROLE_LECTEUR'));

        // Envoie en base de données
    	$em->flush($user);
    }

    public function setUserAction($id){
        // Appele entity Manager
    	$em = $this->getDoctrine()->getManager();
    	
        // va chercher l'utilisateur avec son id
        $user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);

    	// Attribut le role user
    	$user->setRoles(array('ROLE_USER'));

        // Envoie en base de données
    	$em->flush($user);
    }

    public function modifierProfilAction(){
        // Appel entity Manager
        $em = $this->getDoctrine()->getManager();

        // Recupere l'id de l'utilisateur et on recupere l'entité
        $curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
        $b =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
        

        // Cree un formulaire hydrate avec les donnees
        $form = $this->createForm(new UserType(), $b);

        // Recupere la requete
        $request= $this->getRequest();

        // Si le formulaire a ete soumis on envoie les nouvelle infos dans la BDD
        if($request->isMethod("POST")){
            $form->bind($request);
            $b=$form->getData();

            
            $b->setProfilComplet(1);

            $em->persist($b);
            $em->flush();

            // Redirige vers le profil de l'utilisateur
            return $this->redirect($this->generateUrl("fos_user_profile_show"));
        }
        // Si non affiche le fomrulaire 
        return $this->render("ManblioUserBundle:Form:modifierProfil.html.twig", array(
                'form' => $form->createView(),
                'id' => $curentUserId,
            ));
    }

    public function modifierProfilPitcureAction(){
        // Appel entity manager
        $em = $this->getDoctrine()->getManager();

        // Recupere l'id de l'utilisateur courant et crée l'entité
        $curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
        $b =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
        
        // Crée le formulaire     
        $form = $this->createFormBuilder($b)
            ->add('file','file', array('label' => "image de profil"))
            ->getForm();

        // Si le formulaire est soumis on envoie les données
        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());
            if($form->isValid()) {
                $b->upload();
                $em->persist($b);
                $em->flush();

                return $this->redirect($this->generateUrl("fos_user_profile_show"));
            }
        }

        // Si non on affiche le formulaire
        return $this->render("ManblioUserBundle:Form:modifierProfilPicture.html.twig", array(
            'form' => $form->createView(),
            'id' => $curentUserId,
        ));
    }
    
    public function gestionUserAction(){
        // Appel entity manager
        $em = $this->getDoctrine()->getManager();

        // Recupere tout les utilisateurs dans la Base de données
        $users=$em->getRepository('ManblioUserBundle:User')->findAll();

        // Affiche les utilisateurs
        return $this->render("ManblioUserBundle:Admin:Admin.html.twig",array(
            'users' => $users,
            ));
    }

    public function blockUserAction($id){
        // Appel entity manager
        $em = $this->getDoctrine()->getManager();

        if($id != 1){
        // recupere l'utilisateur avec son id
        $user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);

        // Bloque l'utilisateur       
        $user->setLocked(1);

        // Envoie dans la base de donnée
        $em->flush($user);

        // Redirige vers la page d'administrations
        return $this->redirect($this->generateUrl('manblioAdminUsers')."#".$id);
        }
        else{
            die("Ce profil n'es pas modifiable");
        }
    }

    public function deblockUserAction($id){
        // Appel entity manager
        $em = $this->getDoctrine()->getManager();

        // recupere l'entite User avec son id
        $user = $em->getRepository('ManblioUserBundle:User')->findOneById($id);

        // Debloque l'utilisateur
        $user->setLocked(0);

        // Envoie en base de donnée
        $em->flush($user);

        // Redirige vers la page d'administration
        return $this->redirect($this->generateUrl('manblioAdminUsers')."#".$id);
    }

    public function enabledUserAction($id){
        // Appele entity manager
        $em = $this->getDoctrine()->getManager();

        if($id != 1){
        // Recuper l'entite User en base de donnée
        $user=$em->getRepository('ManblioUserBundle:User')->findOneById($id);

        // Active l'utilisateur
        $user->setEnabled(1);

        //envoi en base de données
        $em->flush($user);

        // Redirige vers la page d'administration
        return $this->redirect($this->generateUrl('manblioAdminUsers')."#".$id);
        }
        else{
            die("ce profil n'est pas modifiable");
        }
    }

    public function disabledUserAction($id){
        // Appele entity manager
        $em = $this->getDoctrine()->getManager();
        if($id != 1){
        // Recuper l'entite User en base de donnée
        $user=$em->getRepository('ManblioUserBundle:User')->findOneById($id);

        // Active l'utilisateur
        $user->setEnabled(0);

        //envoi en base de données
        $em->flush($user);

        // Redirige vers la page d'administration
        return $this->redirect($this->generateUrl('manblioAdminUsers')."#".$id);
   
        }
        else{
            die("ce profil n'est pas modifiable");
        }
    }

    public function disabledAccountAction(){
        return $this->render('ManblioUserBundle:Admin:disabledAcount.html.twig');
    }

    public function disabledAction(){
        $em = $this->getDoctrine()->getManager();

        // Recupere l'id de l'utilisateur courant et crée l'entité
        $curentUserId = $this->get('security.context')->getToken()->getUser()->getId();
        if($curentUserId != 1){
            
            $user =  $em->getRepository('ManblioUserBundle:User')->find($curentUserId);
            $user->setEnabled(0);
            $em->flush();

                return $this->redirect($this->generateUrl("fos_user_security_logout"));
        }
        else{
            die("ce profil n'est pas modifiable");
        }
    }
}
