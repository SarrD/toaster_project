<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Utilisateur;

class ProfileController extends DefaultController
{
   /**
   * @Route("/profile/{pseudo}", name="profile")
   */
   public function profile(Request $request, $pseudo)
   {
     //Requete DQL sur "pseudo"
     $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
     //$query = $em->createQuery("SELECT u FROM Utilisateur WHERE  = u.id = :id ");
     //$profile = $query->getResult();
     $user = $users->findOneBy(['id' => $pseudo]);

     if($user == null){
       $this->redirectToRoute('profile_not_found');
     }


     //Creation du tableau de parametres de profil pour le template twig
     //Retour du template rempli
     return $this->render('profile.html.twig', array(
            'nom' => $user->getNom(),
            'prenom'         => $user->getPrenom(),
            'bio'         => $user->getBio(),
            'id'         => $user->getId(),
            'listePubli' => $this->getListeAllPost($pseudo),
            'photo' => $user->getPpPath()
        ));
   }


   /**
   * @Route("/monprofil", name="mon_profil")
   */
   public function monProfil()
   {
      $monId = $this->getUser()->getId();
        return $this->redirectToRoute('profile', array('pseudo' => $monId));

   }

   /**
    * Get Liste publis
    *
    * @return array
    */
   public function getListePostPublics($idUser)
   {
     $query = $this->getDoctrine()->getManager()
     ->createQuery("SELECT p.texte texte, p.datePost datep, p.heurePost heurep,  p.visibilite visibilite, u.ppPath photo, u.prenom prenom, u.nom nom, u.id id
                    FROM 'AppBundle:Utilisateur' u,'AppBundle:Post' p
                    WHERE p.idUtilisateur = u.id
                    AND p.visibilite = 1
                    AND u.id = :id");
     $query->setParameter('id',$idUser);
     $liste = $query->getArrayResult();

       return $liste;
   }

   /**
    * Get Liste all post
    *
    * @return array
    */
   public function getListeAllPost($idUser)
   {
     $query = $this->getDoctrine()->getManager()
     ->createQuery("SELECT p.texte texte, p.datePost datep, p.heurePost heurep,  p.visibilite visibilite, u.ppPath photo, u.prenom prenom, u.nom nom, u.id id
                    FROM 'AppBundle:Utilisateur' u,'AppBundle:Post' p
                    WHERE p.idUtilisateur = u.id
                    AND u.id = :id");
     $query->setParameter('id',$idUser);
     $liste = $query->getArrayResult();

       return $liste;
   }
}
