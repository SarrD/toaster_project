<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Post;
use AppBundle\Entity\Connait;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


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
             'photo' => $user->getPpPath(),
             'monid' => $this->getUser()->getId()
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
                      FROM 'AppBundle:Utilisateur' ue, 'AppBundle:Connait' c
                      WHERE ((c.idUtilisateur1 = ue.id AND c.idUtilisateur2 = :id)
                      OR (c.idUtilisateur2 = ue.id AND c.idUtilisateur1 = :id))
                      AND c.etatRequete = 1
                      AND ue.id != :id");
       $query->setParameter('id',$idUser);
       $liste = $query->getArrayResult();

     //  $liste = [$this,$this,$this];
         return $liste;
     }

     /**
      * Get Liste demandes
      *
      * @return array
      */
     public function getListeDemandes($idUser)
     {
       $query = $this->getDoctrine()->getManager()
       ->createQuery("SELECT u.id id, u.prenom prenom, u.nom nom, u.ppPath ppPath
                      FROM 'AppBundle:Utilisateur' u, 'AppBundle:Connait' c
                      WHERE c.idUtilisateur1 = u.id
                      AND c.etatRequete = 0
                      AND c.idUtilisateur2 = :id");
       $query->setParameter('id',$idUser);
       $liste = $query->getArrayResult();

     //  $liste = [$this,$this,$this];
         return $liste;
     }

     /**
     * @Route("/demandes", name="demandes")
     */
     public function demandes()
     {
       $id = $this->getUser()->getId();
       $listeDemades = $this->getListeDemandes($id);

       return $this->render('demandes.html.twig', array(
              'id'         => $id,
              'personnes' => $listeDemades,
          ));
     }

     /**
     * @Route("/accepter/{pseudo}", name="accepter")
     */
     public function accepter($pseudo)
     {
       $em =$this->getDoctrine()->getManager();
       $relations = $em->getRepository('AppBundle:Connait');
       $relation = $relations->findOneBy(['idUtilisateur1' => $pseudo, 'idUtilisateur2' => $this->getUser()->getId()]);

       $em->remove($relation);
       $relation->setEtatRequete(1);
       //$em->refresh($relation);
       $em->persist($relation);

       //Test si la requete existe dans l'autre sens
       $relation = $relations->findOneBy(['idUtilisateur2' => $pseudo, 'idUtilisateur1' => $this->getUser()->getId()]);
       if ($relation != NULL){
         $em->remove($relation);
       }


       $em->flush();

      return $this->redirectToRoute('profile', array('pseudo' => $pseudo));
     }

     /**
     * @Route("/refuser/{pseudo}", name="refuser")
     */
     public function refuser($pseudo)
     {

       if ($this->getUser()->getId() == $pseudo){
         $this->redirectToRoute('mon_profil');
       }

       $em =$this->getDoctrine()->getManager();
       $relations = $em->getRepository('AppBundle:Connait');
       $relation = $relations->findOneBy(['idUtilisateur1' => $pseudo, 'idUtilisateur2' => $this->getUser()->getId()]);
       if($relation == NULL){
        return $this->redirectToRoute('profile', array('pseudo' => $pseudo));
       }
       $em->remove($relation);
       $em->flush();

      return $this->redirectToRoute('profile', array('pseudo' => $pseudo));
     }

     /**
     * @Route("/retirer/{pseudo}", name="retirer")
     */
     public function retirer(Request $request, $pseudo)
     {
      if ($this->getUser()->getId() == $pseudo){
        $this->redirectToRoute('mon_profil');
      }

       $em = $this->getDoctrine()->getManager();
       $etat = "";
        if ($request->getMethod() == "POST") {
            $this->refuser($pseudo);
            return $this->redirectToRoute('mon_profil');
        }
          $user = $em->getRepository('AppBundle:Utilisateur')->findOneBy(['id' => $pseudo]);

          $page =  $this->render('retrait.html.twig', array(
                 'nom' => $user->getNom(),
                 'prenom'         => $user->getPrenom(),
                 'id'         => $user->getId(),
                 'monid' => $this->getUser()->getId(),
                 'etat' => $etat,
                 'photo' => $this->getUser()->getPpPath()
             ));

        return $page;
     }

     /**
     * @Route("/confirmRetrait/{pseudo}", name="confirm_retrait")
     */
     public function confirmRetrait($pseudo)
     {

       if ($this->getUser()->getId() == $pseudo){
         $this->redirectToRoute('mon_profil');
       }

       $em =$this->getDoctrine()->getManager();
       $relations = $em->getRepository('AppBundle:Connait');
       $relation = $relations->findOneBy(['idUtilisateur1' => $pseudo, 'idUtilisateur2' => $this->getUser()->getId()]);
       if($relation == NULL){
        $relation = $relations->findOneBy(['idUtilisateur2' => $pseudo, 'idUtilisateur1' => $this->getUser()->getId()]);
       }
       $em->remove($relation);
       $em->flush();

      return $this->redirectToRoute('profile', array('pseudo' => $pseudo));
     }

}
