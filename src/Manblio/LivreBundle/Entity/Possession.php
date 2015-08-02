<?php

namespace Manblio\LivreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Manblio\UserBundle\Entity\User;

/**
 * Possession
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Manblio\LivreBundle\Entity\PossessionRepository")
 */
class Possession
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="datetime")
     */
    private $dateAjout;

    /**
     * @ORM\ManyToOne(targetEntity="Livre", inversedBy="possession")
     * @ORM\JoinColumn(name="Livre_id", referencedColumnName="id")
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity="Manblio\UserBundle\Entity\User", inversedBy="possession")
     * @ORM\JoinColumn(name="utilisateur_id", referencedColumnName="id")
     */
    private $utilisateur;

    public function __construct(){
        $this->dateAjout = new \DateTime();
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
     * Set dateAjout
     *
     * @param \DateTime $dateAjout
     * @return Possession
     */
    public function setDateAjout($dateAjout)
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    /**
     * Get dateAjout
     *
     * @return \DateTime 
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * Set livre
     *
     * @param \Manblio\LivreBundle\Entity\Livre $livre
     * @return Possession
     */
    public function setLivre(\Manblio\LivreBundle\Entity\Livre $livre = null)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return \Manblio\LivreBundle\Entity\Livre 
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set utilisateur
     *
     * @param \Manblio\UserBundle\Entity\User $utilisateur
     * @return Possession
     */
    public function setUtilisateur(\Manblio\UserBundle\Entity\User $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Manblio\UserBundle\Entity\User 
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}
