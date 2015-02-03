<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HB\BlogBundle\Entity;
use HB\BlogBundle\Entity\Utilisateur;
use HB\BlogBundle\Form\UtilisateurType;


/**
 * 
 * @author ben
 *
 * @Route("/utilisateur")
 */
class UtilisateurController extends Controller
{
    /**
     * Liste tous les utilisateurs
     * 
     * @Route("/", name="utilisateurs_index")
     * @Template()
     */
    public function indexAction()
    {
    	// On récupère le repository
    	$repository = $this->getDoctrine()->getRepository("HBBlogBundle:Utilisateur");
    	 
    	// On demande au repository tous les utilisateurs
    	$utilisateurs = $repository->findAll();
    	
    	// On transmet nos utilisateurs à la vue
        return array('utilisateurs' => $utilisateurs);
    }

    /**
     * Ajoute un utilisateur
     *
     * @Route("/add", name="utilisateur_add")
     * @Template("HBBlogBundle:Utilisateur:edit.html.twig")
     */
    public function addAction() {
    	$utilisateur = new Utilisateur();
    	return $this->editAction($utilisateur);
    }
    
    /**
     * Affiche un utilisateur sur un id
     *
     * @Route("/{id}", name="utilisateur_read")
     * @Template()
     */
    public function readAction($id) {
    	// on récupère le repository
    	$repository = $this->getDoctrine ()->getRepository ( "HBBlogBundle:Utilisateur" );
    
    	// On demande au repository l'utilisateur par l'id
    	$utilisateur = $repository->find ( $id );
    
    	// On transmet notre utilisateur à la vue
    	return array ( 'utilisateur' => $utilisateur );
    }
    

    /**
     * Affiche un formulaire d'édition sur un id
     *
     * @Route("/{id}/edit", name="utilisateur_edit")
     * route("/titre/{titre}/edit")
     * @Template("HBBlogBundle:Utilisateur:edit.html.twig")
     */
    public function editAction(Utilisateur $utilisateur) {
    	// on a récupéré l'utilisateur grace à un ParamConverter magique
    	// on créé un objet formulaire en lui précisant quel Type utiliser
    	$form = $this->createForm ( new UtilisateurType (), $utilisateur );
    	// On récupère la requête
    	$request = $this->get ( 'request' );
    	// On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
    	if ($request->getMethod () == 'POST') {
    		// On fait le lien Requête <-> Formulaire
    		// À partir de maintenant, la variable $utilisateur contient les valeurs entrées dans
    		// le formulaire par le visiteur
    		$form->bind ( $request );
    		// On vérifie que les valeurs entrées sont correctes
    		// (Nous verrons la validation des objets en détail dans le prochain chapitre)
    		if ($form->isValid ()) {
    			// On l'enregistre notre objet $utilisateur dans la base de données
    			$em = $this->getDoctrine ()->getManager ();
    			$em->persist ( $utilisateur );
    			$em->flush ();
    			// On redirige vers la page de visualisation de l'utilisateur nouvellement créé
    			return $this->redirect ( $this->generateUrl ( 'utilisateur_read', array (
    					'id' => $utilisateur->getId ()
    			) ) );
    		}
    	}
    
    	if ($utilisateur->getId() > 0)
    		$edition = true;
    	else
    		$edition = false;
    
    	// passe la vue de formulaire à la vue
    	return array ('formulaire' => $form->createView (), 'edition' => $edition);
    }
    
    /**
     * Supprime un utilisateur sur un id
     *
     * @Route("/{id}/delete", name="utilisateur_delete")
     * @Template()
     */
    public function deleteAction(Utilisateur $utilisateur) {
    	// on a récupéré l'utilisateur grace à un ParamConverter magique
    
    	// On récupère la requête
    	// $request = $this->get ( 'request' );
    	// Si la méthode est POST, on supprime l'utilisateur
    	// if ($request->getMethod () == 'POST') {
    	$em = $this->getDoctrine()->getEntityManager();
    	$em->remove($utilisateur);
    	$em->flush();
    
    		// on redirige vers la liste des utilisateurs
    		// return $this->redirect($this->generateUrl('utilisateur_index'));
    	//}
    
    	// sinon, on affiche la page de suppression
    	return array( 'utilisateur' => $utilisateur );
    }
}
























