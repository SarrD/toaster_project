<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Utilisateur;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class RechercheController extends DefaultController
{
  /**
   * @Route("/recherche", name="recherche")
   */
    public function recherche(Request $request){

       if ($request->getMethod() == "GET") {

         $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
         $noms = $repo->findBy(['nom' => $request->get('recherche'), 'idRole' => 1]);
         $prenoms = $repo->findBy(['prenom' => $request->get('recherche'), 'idRole' => 1]);
         $tab_personnes = array_merge($noms, $prenoms);
         $tab_personnes = array_unique ($tab_personnes);

         $pageRecherche = $this->render('recherche.html.twig', array(
                'personnes' => $tab_personnes,
                'id'         => $this->getUser()->getId(),
            ));
      }else{
         $pageRecherche =  $this->redirectToRoute('homepage');
      }
      return $pageRecherche;

    }


}
