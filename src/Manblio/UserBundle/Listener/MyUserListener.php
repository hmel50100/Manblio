<?php
namespace Manblio\UserBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

class MyUserListener
{

  private $em;

      function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
  public function chercherUnusedProfil(GetResponseForExceptionEvent $event)
  {
    if(strpos($event->getRequest()->getPathInfo(), 'utilisateur') != false || strpos($event->getRequest()->getPathInfo(), 'user') != false){
      
      $dateA=date('Y-m-d',strtotime('-6 month',strtotime(date('Y-m-d'))));
      $dateB=date('Y-m-d',strtotime('-12 month',strtotime(date('Y-m-d'))));

      $em=$this->em;
      $deleted=$em->getRepository('ManblioUserBundle:User')->findResiduel($dateA);
      $unused=$em->getRepository('ManblioUserBundle:User')->findUnused($dateB);
        if($deleted){
          $test=$this->removeUnusedProfil($delete);
        }
        if($unused){
          $test=$this->removeUnusedProfil($unused);
        }
    }
  }

    public function removeUnusedProfil($user)
    {
      $em=$this->em;
        foreach($user as $u){
            $possession=$em->getRepository('ManblioLivreBundle:possession')->findByUtilisateur($u);
                foreach($possession as $p){
                    $em->remove($p);
                    $em->flush(); 
                }

            $livre=$em->getRepository('ManblioLivreBundle:Livre')->findByQuiCree($u);
            $superAdmin=$em->getRepository('ManblioUserBundle:user')->findOneById(1);
                foreach($livre as $l){
                    $l->setQuiCree($superAdmin);
                    $em->flush(); 
                }

        $em->remove($u);
        $em->flush();

        }
        return 1;
    }
}