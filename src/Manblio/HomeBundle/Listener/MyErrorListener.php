<?php
namespace Manblio\HomeBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class MyErrorListener
{
  private $adresseMail;


  public function __construct($adresseMail){
    $this->adresseMail=$adresseMail;
  }
  public function errorListener(GetResponseForExceptionEvent $event)
  {
      $exception = $event->getException();
      
      if ($exception->getStatusCode() == '404') {
        $this->emailOnNotFound($path);
      }
      else if ($exception->getStatusCode() == '403'){
        $this->emailOnAccesDenied($path);
      }
      else if ($exception->getStatusCode() == '500'){
        $this->emailOnError($path);
      }
      else{
        $this->emailOnGeneralError($path);
      }

  }

    public function emailOnNotFound($path){
      $actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
      $utilisateur = $_SERVER['REMOTE_ADDR'];
      
      $message="NOT FOUND -- ERROR 404 : 
      <br/>la page : " . $actual_link . " n'as pas été trouvé. <br/>
      IP du demandeur : " . $utilisateur;
      
      $title="Manblio.fr - Erreur 404 ";

      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: Erreur Manblio <error@manblio.fr>' . "\r\n";

      mail($this->adresseMail, $title, $message, $headers);
    }

    public function emailOnAccesDenied($path){
      $actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
      $utilisateur = $_SERVER['REMOTE_ADDR'];

      $title="Manblio.fr - Erreur 403 ";
      $message="ACCESS DENIED -- ERROR 403 : <br/ >
      l'ip : " . $utilisateur . " <br/>
      essaye d'acceder à la page : " . $actual_link;

      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: Erreur Manblio <error@manblio.fr>' . "\r\n";

      mail($this->adresseMail, $title, $message, $headers);
    }

    public function emailOnError($path){
      $actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
      $utilisateur = $_SERVER['REMOTE_ADDR'];

      $title="Manblio.fr - Erreur 500 ";
      $message="ERROR ON LOAD  -- ERROR 500 : <br/ >
        La page : ". $actual_link ." Demandé par " . $utilisateur . " Comporte une ou plusieurs erreurs";
      
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: Erreur Manblio <error@manblio.fr>' . "\r\n";

      mail($this->adresseMail, $titles,$message);
    }

    public function emailGeneralError($path){
      $actual_link = "http://".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI];
      $utilisateur = $_SERVER['REMOTE_ADDR'];
      
      $title="Manblio.fr - Erreur 500";
      $message="ERROR ON SITE  : <br/ >
        La page : ". $actual_link ." Demandé par " . $utilisateur . " Comporte une ou plusieurs erreurs";

      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: Erreur Manblio <error@manblio.fr>' . "\r\n";

      mail($this->adresseMail, $title, $message, $headers);
    }

    
}