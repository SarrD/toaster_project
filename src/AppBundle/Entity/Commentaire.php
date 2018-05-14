<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="FK_Commentaire_id_Post", columns={"id_Post"}), @ORM\Index(name="FK_Commentaire_id_Utilisateur", columns={"id_Utilisateur"})})
 * @ORM\Entity
 */
class Commentaire
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
     * @ORM\Column(name="date_commentaire", type="date", nullable=false)
     */
    private $dateCommentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="heure_commentaire", type="string", length=255, nullable=false)
     */
    private $heureCommentaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Post", type="integer", nullable=true)
     */
    private $idPost;

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
     * @return Commentaire
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
     * Set dateCommentaire
     *
     * @param \DateTime $dateCommentaire
     *
     * @return Commentaire
     */
    public function setDateCommentaire($dateCommentaire)
    {
        $this->dateCommentaire = $dateCommentaire;
    
        return $this;
    }

    /**
     * Get dateCommentaire
     *
     * @return \DateTime
     */
    public function getDateCommentaire()
    {
        return $this->dateCommentaire;
    }

    /**
     * Set heureCommentaire
     *
     * @param string $heureCommentaire
     *
     * @return Commentaire
     */
    public function setHeureCommentaire($heureCommentaire)
    {
        $this->heureCommentaire = $heureCommentaire;
    
        return $this;
    }

    /**
     * Get heureCommentaire
     *
     * @return string
     */
    public function getHeureCommentaire()
    {
        return $this->heureCommentaire;
    }

    /**
     * Set idPost
     *
     * @param integer $idPost
     *
     * @return Commentaire
     */
    public function setIdPost($idPost)
    {
        $this->idPost = $idPost;
    
        return $this;
    }

    /**
     * Get idPost
     *
     * @return integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * Set idUtilisateur
     *
     * @param integer $idUtilisateur
     *
     * @return Commentaire
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
