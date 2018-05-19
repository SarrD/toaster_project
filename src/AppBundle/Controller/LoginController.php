<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Role;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class LoginController extends DefaultController
{

    private static $tab_erreurs = array(
                              'connexion_erreur'  => null,
                              'inscription_erreur' => null,);
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
      //Utilisateur deja authentifié
      if($this->getUser() != null){
          $roles = $this->getUser()->getRoles();
          if(in_array("ROLE_ADMIN",$roles)){
              //TODO Route admin
              $redir =  $this->redirectToRoute('sonata_admin_dashboard');
          }else{
            //TODO Route user
              $redir =  $this->redirectToRoute('mon_profil');

          }
      }else {
        // erreur de connexion s'il y en a
        $tab_erreurs['connexion_erreur'] = $authUtils->getLastAuthenticationError();

        // dernier utilisateur connecté
        //TODO ajouter dans le template twig
        $lastUsername = $authUtils->getLastUsername();

        $redir = $this->render('login.html.twig',$tab_erreurs);
      }

        return $redir;
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request, AuthenticationUtils $authUtils)
    {
        return $this->login();
    }

    /**
    * @Route("/register", name="register")
    */
   public function register(Request $request)
   {

       if ($request->getMethod() == "POST") {

//Recuperation  des donnee de formulaire dans un tableau associatif "$params"

           $params['nom'] = $request->request->get('nom');
           $params['prenom'] = $request->request->get('prenom');
           $params['email']  = $request->request->get('email');
           $params['password']  = $request->request->get('password');
           $pass2 = $request->request->get('password2');

           if ($params['password'] != $pass2) {
               $tab_erreurs['inscription_erreur'] = "Les mots de passe ne correspondent pas.";
           }else{

              //// TODO: Requete méthode
              $em = $this->getDoctrine()->getManager();

              $user = new Utilisateur();
              $user->setNom($params['nom']);
              $user->setPrenom($params['prenom']);
              $user->setEmail($params['email']);
              $user->setPassword($params['password']);
              $user->setPpPath('photoProfil.png');

              //$role = $em->getRepository('AppBundle:Role')->find(1); //User
              $user->setIdRole(1); //Role User

              try {
                  $em->persist($user);
                  $em->flush();
                  $tab_erreurs['inscription_erreur'] = "Inscription validée";
                }catch (UniqueConstraintViolationException $e) {
                  $tab_erreurs['inscription_erreur'] = "Cette adresse email est deja utilisée.";
                }
           }
            return $this->render('login.html.twig',$tab_erreurs);
       }else{
          return  $this->redirectToRoute('login');
       }

   }



}
