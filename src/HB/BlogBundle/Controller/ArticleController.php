<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Form\ArticleType;

/**
 *
 * @author ben
 *        
 *         @Route("/article")
 *        
 */
class ArticleController extends Controller {
	
	/**
	 * Liste tous les articles
	 *
	 * @Route("/", name="article_index")
	 * @Template()
	 */
	public function indexAction() {
		// on récupère le repository
		$repository = $this->getDoctrine ()->getRepository ( "HBBlogBundle:Article" );
		
		// On demande au repository tous les articles
		$articles = $repository->findAll ();
		
		// On transmet nos articles à la vue
		return array (
				'articles' => $articles 
		);
	}
	
	/**
	 * Ajoute un article
	 *
	 * @Route("/add", name="article_add")
	 * @Template("HBBlogBundle:Article:edit.html.twig")
	 */
	public function addAction() {
		$article = new Article;
		return $this->editAction($article);
	}
		

	/**
	 * Affiche un article sur un id
	 *
	 * @Route("/{id}", name="article_read")
	 * @Template()
	 */
	public function readAction($id) {
		// on récupère le repository
		$repository = $this->getDoctrine ()->getRepository ( "HBBlogBundle:Article" );
		
		// On demande au repository l'article l'article par l'id
		$article = $repository->find ( $id );
		
		// On transmet notre article à la vue
		return array ( 'article' => $article );
	}
	
	/**
	 * Affiche un formulaire d'édition sur un id
	 *
	 * @Route("/{id}/edit", name="article_edit")
	 * route("/titre/{titre}/edit")
	 * @Template("HBBlogBundle:Article:edit.html.twig")
	 */
	public function editAction(Article $article) {
		// on a récupéré l'article grace à un ParamConverter magique
		// on créé un objet formulaire en lui précisant quel Type utiliser
		$form = $this->createForm ( new ArticleType (), $article );
		// On récupère la requête
		$request = $this->get ( 'request' );
		// On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
		if ($request->getMethod () == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $article contient les valeurs entrées dans
			// le formulaire par le visiteur
			$form->bind ( $request );
			// On vérifie que les valeurs entrées sont correctes
			// (Nous verrons la validation des objets en détail dans le prochain chapitre)
			if ($form->isValid ()) {
				// On l'enregistre notre objet $article dans la base de données
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $article );
				$em->flush ();
				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect ( $this->generateUrl ( 'article_read', array (
						'id' => $article->getId () 
				) ) );
			}
		}
		
		if ($article->getId() > 0)
			$edition = true;
		else
			$edition = false;
		
		// passe la vue de formulaire à la vue
		return array ('formulaire' => $form->createView (), 'edition' => $edition);
	}
	
	/**
	 * Supprime un article sur un id
	 *
	 * @Route("/{id}/delete", name="article_delete")
	 * @Template()
	 */
	public function deleteAction(Article $article) {
		// on a récupéré l'article grace à un ParamConverter magique

		// On récupère la requête
		$request = $this->get ( 'request' );
		// Si la méthode est POST, on supprime l'article
		if ($request->getMethod () == 'POST') {
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($article);
			$em->flush();
		
		// on redirige vers la liste des articles
		return $this->redirect($this->generateUrl('article_index'));
		}
		
		// sinon, on affiche la page de suppression
		return array( 'article' => $article );
	}
}
