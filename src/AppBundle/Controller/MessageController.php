<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Role;


class MessageController extends DefaultController
{

/**
 * @Route("/message", name="message")
 */
public function message(Request $request)
{

    //Recuperation  des donnee de formulaire dans un tableau associatif "$params"
    $params['message'] =null;
    if($request->getMethod()=="POST"){
            $params['message'] = $request->request->get('usermsg');
}
               //Requete DQL sur "pseudo"

               $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
                 $monId = $this->getUser()->getId();
               $user = $repo->findOneBy(['id' => $monId]);

               if($user == null){
                 $this->redirectToRoute('profile_not_found');
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
