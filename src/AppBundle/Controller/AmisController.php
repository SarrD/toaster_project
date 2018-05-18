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
             'amis' => $user->getListeAmis(),
         ));

         return $pageAmis;
     }
}
