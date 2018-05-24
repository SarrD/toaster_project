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


    // -- Envoi du message en base --
    if($request->getMethod()=="POST" && $request->get('method')=="submit"){
            $params['message'] =  $request->get('message');
            $params['pseudo'] =  $request->get('pseudo');
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

            $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Message');
            $id_message = $repo->findOneBy(array(),array('id'=>'DESC'));
            $id_message = $id_message->getId();
            //$id_message = $id_message->getId();


            $response = array('id_message' => $id_message);
            return new JsonResponse($response);

          }

$old_messages_txt =$this->getMessages($user_emmeteur,$user_destinataire);



if($request->getMethod()=="POST" && $request->get('method')=="getMessage"){


  $repository = $this->getDoctrine()
  ->getRepository(Message::class);
// createQueryBuilder() automatically selects FROM AppBundle:Message
// and aliases it to "m"

$query = $this->getDoctrine()->getManager()
->createQuery("SELECT ue.nom nom_emmeteur, ue.prenom prenom_emmeteur, m.texte, m.id id
            FROM 'AppBundle:Utilisateur' ue, 'AppBundle:Utilisateur' ud, 'AppBundle:Message' m
            WHERE m.idEmmeteur = ue.id
            AND m.idDestinataire=ud.id
            AND ((ud.id=:dest AND ue.id=:emmet) OR (ud.id=:emmet AND ue.id=:dest))
            AND m.id > :id
            ORDER BY m.id DESC");



$query->setParameter('emmet',$user_emmeteur->getId())
    ->setParameter('dest',$user_destinataire->getId())
    ->setParameter('id',$request->get('id_message'));

$last_message = $query->getArrayResult();
  $id_message = $repo->findOneBy(array(),array('id'=>'DESC'));
  //$id_message = $id_message->getId();
  if($id_message){
    $id_message= new Message();
  }

$response = array('message' =>$last_message,
  'id_message'=>$id_message,
);


return new JsonResponse($response);

}

               //Creation du tableau de parametres de profil pour le template twig
               //Retour du template rempli
               return $this->render('pageMessage.html.twig', array(
                      'nom' => $user_emmeteur->getNom(),
                      'prenom' => $user_emmeteur->getPrenom(),
                      'nom_destinataire' => $user_destinataire->getNom(),
                      'prenom_destinataire' => $user_destinataire->getPrenom(),
                      'id' => $user_destinataire->getId(),
                      'old_messages' => $old_messages_txt,
                      'id_message' => $id_message,
                      'amis' => $this->getListeAmis($monId),
                      //'message' => $last_message,
                  ));
}




/**
* Get Ancien Message
*
*@param AppBundle:Utilisateur $user_emmeteur utilisateur emmeteur
*@param AppBundle:Utilisateur $user_destinataire utilisateur destinataire
* @return array les message
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



}
