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
         /*
         $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
         $noms = $repo->findBy(['nom' => $request->get('recherche'), 'idRole' => 1]);
         $prenoms = $repo->findBy(['prenom' => $request->get('recherche'), 'idRole' => 1]);
         $tab_personnes = array_merge($noms, $prenoms);
         $tab_personnes = array_unique ($tab_personnes);
         */
         $query = $this->getDoctrine()->getManager()
         ->createQuery("SELECT u.id id, u.prenom prenom, u.nom nom, u.ppPath ppPath
                        FROM 'AppBundle:Utilisateur' u
                        WHERE (LOWER(u.nom) LIKE :requete
                        OR LOWER(u.prenom) LIKE :requete
                        OR CONCAT(u.prenom, ' ', u.nom) LIKE :requete)
                        AND u.idRole = 1
                        ");
         $query->setParameter('requete', '%'.strtolower(trim($request->get('recherche'))).'%');
         $tab_personnes = $query->getArrayResult();


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
