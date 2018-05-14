<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="FK_Post_id_Utilisateur", columns={"id_Utilisateur"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_post", type="date", nullable=false)
     */
    private $datePost;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_post", type="time", nullable=false)
     */
    private $heurePost;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibilite", type="boolean", nullable=false)
     */
    private $visibilite;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set texte
     *
     * @param string $texte
     *
     * @return Post
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    
        return $this;
    }

    /**
     * Get texte
     *
     * @return string
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set datePost
     *
     * @param \DateTime $datePost
     *
     * @return Post
     */
    public function setDatePost($datePost)
    {
        $this->datePost = $datePost;
    
        return $this;
    }

    /**
     * Get datePost
     *
     * @return \DateTime
     */
    public function getDatePost()
    {
        return $this->datePost;
    }

    /**
     * Set heurePost
     *
     * @param \DateTime $heurePost
     *
     * @return Post
     */
    public function setHeurePost($heurePost)
    {
        $this->heurePost = $heurePost;
    
        return $this;
    }

    /**
     * Get heurePost
     *
     * @return \DateTime
     */
    public function getHeurePost()
    {
        return $this->heurePost;
    }

    /**
     * Set visibilite
     *
     * @param boolean $visibilite
     *
     * @return Post
     */
    public function setVisibilite($visibilite)
    {
        $this->visibilite = $visibilite;
    
        return $this;
    }

    /**
     * Get visibilite
     *
     * @return boolean
     */
    public function getVisibilite()
    {
        return $this->visibilite;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return Post
     */
    public function setIdUtilisateur($idUtilisateur)
    {
        $this->idUtilisateur = $idUtilisateur;
    
        return $this;
    }

    /**
     * Get idUtilisateur
     *
     * @return integer
     */
    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
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
}
