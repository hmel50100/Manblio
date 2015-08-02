<?php

namespace Manblio\LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * NomSerie
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Manblio\LivreBundle\Entity\NomSerieRepository")
 */
class NomSerie
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomSerie", type="text")
     */
    private $nomSerie;

    /**
     * @var text
     *
     * @ORM\Column(name="auteur", type="text")
     */
    private $auteur;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Livre", mappedBy="nomSerie")
     */
    protected $livre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isFinish", type="boolean")
     */
    private $isFinish;

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
        $this->livre= new arrayCollection();
    }

    public function __toString(){
        return $this->nomSerie;
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
     * Set nomSerie
     *
     * @param string $nomSerie
     * @return NomSerie
     */
    public function setNomSerie($nomSerie)
    {
        $this->nomSerie = $nomSerie;

        return $this;
    }

    /**
     * Get nomSerie
     *
     * @return string 
     */
    public function getNomSerie()
    {
        return $this->nomSerie;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return NomSerie
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add livre
     *
     * @param \Manblio\LivreBundle\Entity\Livre $livre
     * @return NomSerie
     */
    public function addLivre(\Manblio\LivreBundle\Entity\Livre $livre)
    {
        $this->livre[] = $livre;

        return $this;
    }

    /**
     * Remove livre
     *
     * @param \Manblio\LivreBundle\Entity\Livre $livre
     */
    public function removeLivre(\Manblio\LivreBundle\Entity\Livre $livre)
    {
        $this->livre->removeElement($livre);
    }

    /**
     * Get livre
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return NomSerie
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
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
        return 'images/nomSerie';
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
            substr(hash('sha512',sha1(rand(1,9999))),-10).'.'.$this->file->guessExtension());
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
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set isFinish
     *
     * @param boolean $isFinish
     * @return NomSerie
     */
    public function setIsFinish($isFinish)
    {
        $this->isFinish = $isFinish;

        return $this;
    }

    /**
     * Get isFinish
     *
     * @return boolean 
     */
    public function getIsFinish()
    {
        return $this->isFinish;
    }
}
