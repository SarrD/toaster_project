<?php
// src/AppBundle/Controller/PhotoController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Utilisateur;
use AppBundle\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

class PhotoController extends Controller
{
    /**
     * @Route("/changerphoto", name="app_product_new")
     */
    public function newAction(Request $request)
    {
      $file = null;

      $users = $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur');
      //$query = $em->createQuery("SELECT u FROM Utilisateur WHERE  = u.id = :id ");
      //$profile = $query->getResult();

      $user = $users->findOneBy(['id' =>  $this->getUser()->getId()]);


      if($user == null){
        $this->redirectToRoute('profile_not_found');
      }
      $user_file=$user->setPpPath(
   new File($this->getParameter('brochures_directory').'/'.$user->getPpPath())
);

        $form = $this->createForm(UtilisateurType::class,$user_file);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getPpPath();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );
            $file=null;
/*
            $em = $this->getDoctrine()->getManager();
            $user->setNom("photo");
            $user->setPrenom("photo");
            $user->setEmail("photo@mail.fr");
            $user->setPassword("photo");*/
            $user->setPpPath($fileName);

            //$role = $em->getRepository('AppBundle:Role')->find(1); //User
          //  $user->setIdRole(1); //Role User

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            // updates the 'brochure' property to store the PDF file name
            // instead of its contents


            // ... persist the $product variable or any other work

            return $this->redirectToRoute("login");
        }

        return $this->render('photo.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'id' =>$this->getUser()->getId(),
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
