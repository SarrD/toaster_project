<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connait
 *
 * @ORM\Table(name="connait", uniqueConstraints={@ORM\UniqueConstraint(name="id_Utilisateur1", columns={"id_Utilisateur1", "id_Utilisateur2"})}, indexes={@ORM\Index(name="IDX_8929B949EA59BF68", columns={"id_Utilisateur1"}), @ORM\Index(name="FK_Connait_id2", columns={"id_Utilisateur2"})})
 * @ORM\Entity
 */
class Connait
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="etat_requete", type="boolean", nullable=true)
     */
    private $etatRequete;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Utilisateur1", type="integer", nullable=true)
     */
    private $idUtilisateur1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Utilisateur2", type="integer", nullable=true)
     */
    private $idUtilisateur2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set etatRequete
     *
     * @param boolean $etatRequete
     *
     * @return Connait
     */
    public function setEtatRequete($etatRequete)
    {
        $this->etatRequete = $etatRequete;
    
        return $this;
    }

    /**
     * Get etatRequete
     *
     * @return boolean
     */
    public function getEtatRequete()
    {
        return $this->etatRequete;
    }

    /**
     * Set idUtilisateur1
     *
     * @param integer $idUtilisateur1
     *
     * @return Connait
     */
    public function setIdUtilisateur1($idUtilisateur1)
    {
        $this->idUtilisateur1 = $idUtilisateur1;
    
        return $this;
    }

    /**
     * Get idUtilisateur1
     *
     * @return integer
     */
    public function getIdUtilisateur1()
    {
        return $this->idUtilisateur1;
    }

    /**
     * Set idUtilisateur2
     *
     * @param integer $idUtilisateur2
     *
     * @return Connait
     */
    public function setIdUtilisateur2($idUtilisateur2)
    {
        $this->idUtilisateur2 = $idUtilisateur2;
    
        return $this;
    }

    /**
     * Get idUtilisateur2
     *
     * @return integer
     */
    public function getIdUtilisateur2()
    {
        return $this->idUtilisateur2;
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
