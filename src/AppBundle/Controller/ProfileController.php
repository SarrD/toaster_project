<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Post;
use AppBundle\Entity\Connait;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ProfileController extends DefaultController
{
   /**
   * @Route("/profile/{pseudo}", name="profile")
   */
   public function profile(Request $request, $pseudo)
   {
     if ($request->getMethod() == "POST") {


         $publication= $request->request->get('publication');

         if ($publication == "" && false) {
             //Alert ?
         }else{


            $em = $this->getDoctrine()->getManager();

            $publipost = new Post();
            $publipost->setTexte($publication);
            $publipost->setDatePost(new \DateTime());
            $publipost->setHeurePost(new \DateTime());
            $publipost->setVisibilite(1);
            $publipost->setIdUtilisateur($this->getUser()->getId());
            $em->persist($publipost);
            $em->flush();

            // $this->monProfil();

            return new JsonResponse(array(
              'heure' =>date('H:i:s'),
              'date' =>date('Y-m-d'),
              'id' =>$this->getUser()->getId(),
              'nom' =>$this->getUser()->getNom(),
              'prenom' => $this->getUser()->getPrenom(),
              'photo' => $this->getUser()->getPpPath()

            ));
         }

         }


     //Requete DQL sur "pseudo"
     $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
     //$query = $em->createQuery("SELECT u FROM Utilisateur WHERE  = u.id = :id ");
     //$profile = $query->getResult();
     $user = $users->findOneBy(['id' => $pseudo]);

     if($user == null){
       $this->redirectToRoute('profile_not_found');
     }
      if($this->getUser()->getId() != $pseudo){
         $relations = $this->getDoctrine()->getManager()->getRepository('AppBundle:Connait');
         $relation = $relations->findOneBy(['idUtilisateur1' => $pseudo]);

         if($relation == NULL){
           $relation = $relations->findOneBy(['idUtilisateur2' => $pseudo]);
         }

         if($relation == NULL){
           $template_profil = 'publicprofile.html.twig'; //non ami = profil public
         }else {
           if ($relation->getEtatRequete()) {
              $template_profil = 'profile.html.twig'; // ami = profil privé
           }else{
              $template_profil = 'publicprofile.html.twig';//requete non validée = profil public
           }
         }
       }else{
           $template_profil = 'profile.html.twig'; // perso = profil privé
       }


     //Creation du tableau de parametres de profil pour le template twig
     //Retour du template rempli
     return $this->render($template_profil, array(
            'nom' => $user->getNom(),
            'prenom'         => $user->getPrenom(),
            'bio'         => $user->getBio(),
            'id'         => $user->getId(),
            'listePubli' => $this->getListeAllPost($pseudo),
            'photo' => $user->getPpPath(),
            'monid' => $this->getUser()->getId()
        ));
   }

   /**
   * @Route("/ajouter/{pseudo}", name="ajouter")
   */
   public function ajouter(Request $request, $pseudo)
   {
     if($this->getUser()->getId() == $pseudo){
       return $this->redirectToRoute('mon_profil');
     }


     $em = $this->getDoctrine()->getManager();
     $etat = "";
      if ($request->getMethod() == "POST") {

          $demande = new Connait();
          $demande->setEtatRequete(false);
          $demande->setIdUtilisateur1($this->getUser()->getId());
          $demande->setIdUtilisateur2($pseudo);

          try {
              $em->persist($demande);
              $em->flush();
              $etat = "Demande envoyée";
            }catch (UniqueConstraintViolationException $e) {
              $etat = "Vous avez deja ajouté cet utilisateur ou il vous a deja ajouté";
            }
          }
            $user = $em->getRepository('AppBundle:Utilisateur')->findOneBy(['id' => $pseudo]);

        $page =  $this->render('ajout.html.twig', array(
               'nom' => $user->getNom(),
               'prenom'         => $user->getPrenom(),
               'bio'         => $user->getBio(),
               'id'         => $user->getId(),
               'listePubli' => $this->getListeAllPost($pseudo),
               'photo' => $user->getPpPath(),
               'monid' => $this->getUser()->getId(),
               'etat' => $etat
           ));

      return $page;
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
   * @Route("/timeline", name="timeline")
   */
   public function timeline(Request $request)
   {
     if ($request->getMethod() == "POST") {


         $publication= $request->request->get('publication');

         if ($publication == "" && false) {
             //Alert ?
         }else{


            $em = $this->getDoctrine()->getManager();

            $publipost = new Post();
            $publipost->setTexte($publication);
            $publipost->setDatePost(new \DateTime());
            $publipost->setHeurePost(new \DateTime());
            $publipost->setVisibilite(1);
            $publipost->setIdUtilisateur($this->getUser()->getId());
            $em->persist($publipost);
            $em->flush();

            // $this->monProfil();

            return new JsonResponse(array(
              'heure' =>date('H:i:s'),
              'date' =>date('Y-m-d'),
              'id' =>$this->getUser()->getId(),
              'nom' =>$this->getUser()->getNom(),
              'prenom' => $this->getUser()->getPrenom(),
              'photo' => $this->getUser()->getPpPath()

            ));
         }

         }

        $user = $this->getUser();

         return $this->render('timeline.html.twig', array(
                'nom' => $user->getNom(),
                'prenom'         => $user->getPrenom(),
                'id'         => $user->getId(),
                'listePubli' => $this->getListePostAmis(),
                'photo' => $user->getPpPath(),
                'monid' => $this->getUser()->getId()
            ));

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
                    AND u.id = :id
                    ORDER BY p.datePost DESC, p.heurePost DESC ");
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
                    AND u.id = :id
                    ORDER BY p.datePost DESC, p.heurePost DESC ");
     $query->setParameter('id',$idUser);
     $liste = $query->getArrayResult();

       return $liste;
   }

   /**
    * Get Liste publis des amis
    *
    * @return array
    */
   public function getListePostAmis()
   {
     $query = $this->getDoctrine()->getManager()
     ->createQuery("SELECT p.texte texte, p.datePost datep, p.heurePost heurep,  p.visibilite visibilite, u.ppPath photo, u.prenom prenom, u.nom nom, u.id id
                    FROM 'AppBundle:Utilisateur' u,'AppBundle:Post' p, 'AppBundle:Connait' c
                    WHERE (c.idUtilisateur1 = :id
                    OR c.idUtilisateur2 = :id)
                    AND c.etatRequete = 1
                    AND p.idUtilisateur = u.id
                    ORDER BY p.datePost DESC, p.heurePost DESC ");
     $query->setParameter('id',$this->getUser()->getId());
     $liste = $query->getArrayResult();

       return $liste;
   }


}
