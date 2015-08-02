<?php

namespace Manblio\UserBundle\Entity;

use FOS\UserBundle\Model\User as baseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Manblio\LivreBundle\Entity\Possession;

/**
 * @ORM\Entity
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="Manblio\UserBundle\Entity\UserRepository")
 */

class User extends BaseUser{
	/**
	 *@ORM\Id
	 *@ORM\Column(type="integer")
	 *@ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * @ORM\OneToMany(targetEntity="Manblio\LivreBundle\Entity\Possession", mappedBy="utilisateur")
     */
    protected $possession;

    /**
     * @var boolean
     *
     * @ORM\Column(name="profilComplet", type="boolean")
     */
    private $profilComplet;

    /**
     * @var boolean
     *
     * @ORM\Column(name="accepteCGU", type="boolean")
     */
    private $accepteCGU;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\ManyToOne(targetEntity="Manblio\HomeBundle\Entity\departements")
     */
    private $departement;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $fileName; 

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file; 
    
    
     // propriété utilisé temporairement pour la suppression
    private $filenameForRemove;


	public function __construct(){
		parent::__construct();
        $this->roles = array('ROLE_USER');
        $this->accepteCGU=true;
		$this->possession= new arrayCollection();
        $this->profilComplet=0;        
	}

    public function __toString(){
        return $this->username;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../www/fichiers/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'images/imageProfil';
    }


        /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->path = $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        $this->setFileName(
           $this->getId().'.'.$this->file->guessExtension());
        if($this->file->move($this->getUploadRootDir(), $this->fileName)){
            unset($this->file);
        }
        else {
            die();
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return User
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set profilComplet
     *
     * @param boolean $profilComplet
     * @return User
     */
    public function setProfilComplet($profilComplet)
    {
        $this->profilComplet = $profilComplet;

        return $this;
    }

    /**
     * Get profilComplet
     *
     * @return boolean 
     */
    public function getProfilComplet()
    {
        return $this->profilComplet;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Add possession
     *
     * @param \Manblio\LivreBundle\Entity\Possession $possession
     * @return User
     */
    public function addPossession(\Manblio\LivreBundle\Entity\Possession $possession)
    {
        $this->possession[] = $possession;

        return $this;
    }

    /**
     * Remove possession
     *
     * @param \Manblio\LivreBundle\Entity\Possession $possession
     */
    public function removePossession(\Manblio\LivreBundle\Entity\Possession $possession)
    {
        $this->possession->removeElement($possession);
    }

    /**
     * Get possession
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPossession()
    {
        return $this->possession;
    }

    

    /**
     * Set departement
     *
     * @param \Manblio\HomeBundle\Entity\departements $departement
     * @return User
     */
    public function setDepartement(\Manblio\HomeBundle\Entity\departements $departement = null)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \Manblio\HomeBundle\Entity\departements 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set accepteCGU
     *
     * @param boolean $accepteCGU
     * @return User
     */
    public function setAccepteCGU($accepteCGU)
    {
        $this->accepteCGU = $accepteCGU;

        return $this;
    }

    /**
     * Get accepteCGU
     *
     * @return boolean 
     */
    public function getAccepteCGU()
    {
        return $this->accepteCGU;
    }
}
