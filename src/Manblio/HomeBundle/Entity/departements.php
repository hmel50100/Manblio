<?php

namespace Manblio\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * departements
 */
class departements
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $nomUpperCase;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $nomSoundex;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function __toString(){
        return $this->code." - " .$this->nom;
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
     * Set code
     *
     * @param string $code
     * @return departements
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return departements
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nomUpperCase
     *
     * @param string $nomUpperCase
     * @return departements
     */
    public function setNomUpperCase($nomUpperCase)
    {
        $this->nomUpperCase = $nomUpperCase;

        return $this;
    }

    /**
     * Get nomUpperCase
     *
     * @return string 
     */
    public function getNomUpperCase()
    {
        return $this->nomUpperCase;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return departements
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nomSoundex
     *
     * @param string $nomSoundex
     * @return departements
     */
    public function setNomSoundex($nomSoundex)
    {
        $this->nomSoundex = $nomSoundex;

        return $this;
    }

    /**
     * Get nomSoundex
     *
     * @return string 
     */
    public function getNomSoundex()
    {
        return $this->nomSoundex;
    }
}
