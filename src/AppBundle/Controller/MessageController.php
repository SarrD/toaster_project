<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Role;
use AppBundle\Entity\Message;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends DefaultController
{

/**
 * @Route("/message/{id_destinataire}", name="message")
 */
public function message(Request $request, $id_destinataire)
{

    //Recuperation  des donnee de formulaire dans un tableau associatif "$params"

    $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
      $monId = $this->getUser()->getId();

        $user_emmeteur = $repo->findOneBy(['id' => $monId]);
        $user_destinataire = $repo->findOneBy(['id' => $id_destinataire]);

        if($user_emmeteur == null || $user_destinataire == null){
          $this->redirectToRoute('profile_not_found');
        }

        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Message');
      $id_message = $repo->findOneBy(array(),array('id'=>'DESC'));
      $id_message = $id_message->getId();

    // -- Envoi du message en base --
    if($request->getMethod()=="POST" && $_POST['method']=="submit"){
            $params['message'] = $_POST['message'];
            $params['pseudo'] = $_POST['pseudo'];
            $params['date']= date("Y-m-d H:i:s");
            $params['heure']=new \DateTime();
            $params['id']=$monId;

            //texte,dateenvoie,id,iduser,heureEnvoie

            $em = $this->getDoctrine()->getManager();

            $message = new Message();
            $message->setTexte($params['message']);
            $message->setDateEnvoi($params['heure']);
            $message->setIdEmmeteur($params['id']);
            $message->setIdDestinataire($id_destinataire);
            $message->setHeureEnvoi($params['heure']);
            $em->persist($message);
            $em->flush();

            $id_message = $repo->findOneBy(array(),array('id'=>'DESC'));
            $id_message = $id_message->getId();
            $response = array('id_message' => $id_message);
            return new JsonResponse($response);

          }

$old_messages_txt =$this->getMessages($user_emmeteur,$user_destinataire);


if($request->getMethod()=="POST" && $_POST['method']=="getMessage"){
      $repository = $this->getDoctrine()
      ->getRepository(Message::class);
  // createQueryBuilder() automatically selects FROM AppBundle:Message
  // and aliases it to "m"
  $query = $repository->createQueryBuilder('m')
      ->where('m.id > :id')
      ->setParameter('id', $_POST['id_message'])
      ->orderBy('m.id', 'DESC')
      ->getQuery();

  $last_message = $query->getArrayResult();

$response = array('message' =>$last_message);


return new JsonResponse($response);

}

               //Creation du tableau de parametres de profil pour le template twig
               //Retour du template rempli
               return $this->render('pageMessage.html.twig', array(
                      'nom' => $user_emmeteur->getNom(),
                      'prenom' => $user_emmeteur->getPrenom(),
                      'nom_destinataire' => $user_destinataire->getNom(),
                      'prenom_destinataire' => $user_destinataire->getPrenom(),
                      'id_destinataire' => $user_destinataire->getId(),
                      'old_messages' => $old_messages_txt,
                      'id_message' => $id_message,
                      //'message' => $last_message,
                  ));
}




/**
    * Get Ancien Message
    *
    * @return array
    */
function getMessages($user_emmeteur,$user_destinataire){
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

    return $old_messages_txt;
}

}
