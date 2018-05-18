<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Utilisateur;

class AmisController extends DefaultController
{
   /**
   * @Route("/amis/{pseudo}", name="amis")
   */
   public function amis(Request $request, $pseudo )
   {

     $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');

     $user = $users->findOneBy(['id' => $pseudo]);

     if($user == null){
       $this->redirectToRoute('profile_not_found');
     }

      $pageAmis = $this->render('pageAmis.html.twig', array(
             'nom' => $user->getNom(),
             'prenom'         => $user->getPrenom(),
             'bio'         => $user->getBio(),
             'id'         => $user->getId(),
             'amis' => $this->getListeAmis($user->getId()),
             'photo' => $user->getPpPath()
         ));

         return $pageAmis;
     }

     /**
      * Get Liste Amis
      *
      * @return array
      */
     public function getListeAmis($idUser)
     {
       $query = $this->getDoctrine()->getManager()
       ->createQuery("SELECT ue.id id, ue.prenom prenom, ue.nom nom, ue.ppPath photo
                      FROM 'AppBundle:Utilisateur' ud,'AppBundle:Utilisateur' ue, 'AppBundle:Connait' c
                      WHERE (c.idUtilisateur1 = ue.id
                      OR c.idUtilisateur2 = ue.id)
                      AND c.etatRequete = 1
                      AND ud.id = :id
                      AND ue.id != :id");
       $query->setParameter('id',$idUser);
       $liste = $query->getArrayResult();

     //  $liste = [$this,$this,$this];
         return $liste;
     }
}
