<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * 
 * @author ben
 *
 * @Route("/article")
 *
 */
class ArticleController extends Controller
{
	/**
	 * Liste tous les articles
	 * 
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction()
	{
		return array();
	}

	/**
	 * Ajoute un article
	 *
	 * @Route("/add")
	 * @Template()
	 */
	public function addAction()
	{
		return array();
	}
	
	/**
	 * Affiche un article sur un id
	 * 
	 * @Route("/{id}")
	 * @Template()
	 */
	public function readAction($id)
	{
		// on récupère le repository
		$repository = $this->getDoctrine()->getRepository("HBBlogBundle:Article");
		
		// On transmet notre article à la vue
		$article = $repository->find($id);
		
		return array('article' => $article);
	}

	/**
	 * Affiche un formulaire d'édition sur un id
	 *
	 * @Route("/{id}/edit")
	 * @Template()
	 */
	public function editAction($id)
	{
		return array('id' => $id);
	}
	
	/**
	 * Supprime un article sur un id
	 *
	 * @Route("/{id}/delete")
	 * @Template()
	 */
	public function deleteAction($id)
	{
		return array('id' => $id);
	}
	
}
