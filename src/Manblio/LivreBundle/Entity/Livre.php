<?php

namespace Manblio\LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Livre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Manblio\LivreBundle\Entity\LivreRepository")
 */
class Livre
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
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity="NomSerie", inversedBy="livre")
     * @ORM\JoinColumn(name="nomSerie_id", referencedColumnName="id")
     */
    private $nomSerie;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;
    
    /**
    * @ORM\ManyToOne(targetEntity="Manblio\UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $quiCree;
    
    /**
     * @ORM\OneToMany(targetEntity="Possession", mappedBy="livre")
     */
    protected $possession;


    public function __construct(){
        $this->utilisateur = new arrayCollection();
    }
    public function __toString(){
        return $this->numero;
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
     * Set numero
     *
     * @param integer $numero
     * @return Livre
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set nomSerie
     *
     * @param string $nomSerie
     * @return Livre
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
     * Set prix
     *
     * @param float $prix
     * @return Livre
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set note
     *
     * @param integer $note
     * @return Livre
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Add utilisateur
     *
     * @param \Manblio\LivreBundle\Entity\Possession $utilisateur
     * @return Livre
     */
    public function addUtilisateur(\Manblio\LivreBundle\Entity\Possession $utilisateur)
    {
        $this->utilisateur[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Manblio\LivreBundle\Entity\Possession $utilisateur
     */
    public function removeUtilisateur(\Manblio\LivreBundle\Entity\Possession $utilisateur)
    {
        $this->utilisateur->removeElement($utilisateur);
    }

    /**
     * Get utilisateur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add possession
     *
     * @param \Manblio\LivreBundle\Entity\Possession $possession
     * @return Livre
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
     * Set quiCree
     *
     * @param \Manblio\UserBundle\Entity\User $quiCree
     * @return Livre
     */
    public function setQuiCree(\Manblio\UserBundle\Entity\User $quiCree = null)
    {
        $this->quiCree = $quiCree;

        return $this;
    }

    /**
     * Get quiCree
     *
     * @return \Manblio\UserBundle\Entity\User 
     */
    public function getQuiCree()
    {
        return $this->quiCree;
    }
}
