<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Role;
use AppBundle\Entity\Message;


class MessageController extends DefaultController
{

/**
 * @Route("/message/{id_destinataire}", name="message")
 */
public function message(Request $request, $id_destinataire)
{

    //Recuperation  des donnee de formulaire dans un tableau associatif "$params"
    $params['message'] =null;

    $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
      $monId = $this->getUser()->getId();

        $user_emmeteur = $repo->findOneBy(['id' => $monId]);
        $user_destinataire = $repo->findOneBy(['id' => $id_destinataire]);

        if($user_emmeteur == null || $user_destinataire == null){
          $this->redirectToRoute('profile_not_found');
        }

    // -- Envoi du message en base --
    if($request->getMethod()=="POST"){
            $params['message'] = $_POST['message'];
            $params['pseudo'] = $_POST['pseudo'];
            $params['date']= date("Y-m-d H:i:s");
            $params['heure']=new \DateTime();
            $params['id']=$monId;

            //texte,dateenvoie,id,iduser,heureEnvoie

            $em = $this->getDoctrine()->getManager();

            $message = new Message();
            $message->setTexte($params['pseudo'].$params['message']);
            $message->setDateEnvoi($params['heure']);
            $message->setIdEmmeteur($params['id']);
            $message->setHeureEnvoi($params['heure']);
            $em->persist($message);
            $em->flush();

          }

          //Recupere les messages envoyés précedemments depuis la base
            $query = $this->getDoctrine()->getManager()
            ->createQuery("SELECT ue.nom nom_emmeteur, ue.prenom prenom_emmeteur, ud.nom nom_destinataire, ud.prenom prenom_destinataire, m.texte
                          FROM 'AppBundle:Utilisateur' ue, 'AppBundle:Utilisateur' ud, 'AppBundle:Message' m
                          WHERE m.idEmmeteur = ue.id
                          AND m.idDestinataire=ud.id
                          AND ((ud.id=:dest AND ue.id=:emmet) OR (ud.id=:emmet AND ue.id=:dest))");
            $query->setParameter('emmet',$user_emmeteur->getId())
                  ->setParameter('dest',$user_destinataire->getId());
            $old_messages_txt = $query->getArrayResult();

               //Creation du tableau de parametres de profil pour le template twig
               //Retour du template rempli
               return $this->render('pageMessage.html.twig', array(
                      'nom' => $user_emmeteur->getNom(),
                      'prenom' => $user_emmeteur->getPrenom(),
                      'bio' => $user_emmeteur->getBio(),
                      'mess' =>$params['message'],
                      'nom_destinataire' => $user_destinataire->getNom(),
                      'prenom_destinataire' => $user_destinataire->getPrenom(),
                      'id_destinataire' => $user_destinataire->getId(),
                      'old_messages' => $old_messages_txt,
                  ));


}

}
