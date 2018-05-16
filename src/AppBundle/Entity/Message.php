<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message", indexes={@ORM\Index(name="FK_Message_id_Utilisateur1", columns={"id_Emmeteur"}), @ORM\Index(name="FK_Message_id_Utilisateur2", columns={"id_Destinataire"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @var string
     *
     * @ORM\Column(name="texte", type="string", length=255, nullable=false)
     */
    private $texte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="date", nullable=false)
     */
    private $dateEnvoi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_envoi", type="time", nullable=false)
     */
    private $heureEnvoi;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Emmeteur", type="integer", nullable=true)
     */
    private $idEmmeteur;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_Destinataire", type="integer", nullable=true)
     */
    private $idDestinataire;

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
     * @return Message
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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     *
     * @return Message
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    
        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set heureEnvoi
     *
     * @param \DateTime $heureEnvoi
     *
     * @return Message
     */
    public function setHeureEnvoi($heureEnvoi)
    {
        $this->heureEnvoi = $heureEnvoi;
    
        return $this;
    }

    /**
     * Get heureEnvoi
     *
     * @return \DateTime
     */
    public function getHeureEnvoi()
    {
        return $this->heureEnvoi;
    }

    /**
     * Set idEmmeteur
     *
     * @param integer $idEmmeteur
     *
     * @return Message
     */
    public function setIdEmmeteur($idEmmeteur)
    {
        $this->idEmmeteur = $idEmmeteur;
    
        return $this;
    }

    /**
     * Get idEmmeteur
     *
     * @return integer
     */
    public function getIdEmmeteur()
    {
        return $this->idEmmeteur;
    }

    /**
     * Set idDestinataire
     *
     * @param integer $idDestinataire
     *
     * @return Message
     */
    public function setIdDestinataire($idDestinataire)
    {
        $this->idDestinataire = $idDestinataire;
    
        return $this;
    }

    /**
     * Get idDestinataire
     *
     * @return integer
     */
    public function getIdDestinataire()
    {
        return $this->idDestinataire;
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
