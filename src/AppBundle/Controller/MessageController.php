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
 * @Route("/message", name="message")
 */
public function message(Request $request)
{

    //Recuperation  des donnee de formulaire dans un tableau associatif "$params"
    $params['message'] =null;

    $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
      $monId = $this->getUser()->getId();

        $user = $repo->findOneBy(['id' => $monId]);

        if($user == null){
          $this->redirectToRoute('profile_not_found');
        }
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
            $message->setIdUtilisateur($params['id']);
            $message->setHeureEnvoi($params['heure']);

            $em->persist($message);

    // actually executes the queries (i.e. the INSERT query)
    $em->flush();

}

               //Creation du tableau de parametres de profil pour le template twig
               //Retour du template rempli
               return $this->render('pageMessage.html.twig', array(
                      'nom' => $user->getNom(),
                      'prenom' => $user->getPrenom(),
                      'bio' => $user->getBio(),
                      'mess' =>$params['message'],

                  ));


}

}
