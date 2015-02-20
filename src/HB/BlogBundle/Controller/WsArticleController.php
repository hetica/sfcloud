<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Form\ArticleType;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 *
 * @author ben
 *        
 */
class WsArticleController extends Controller {
	
	/**
	 * Liste tous les articles
	 *
	 * @Soap\Method("getIndexArticles")
	 * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article[]")
	 */
	public function getIndexArticlesAction() {
		// on récupère le repository
		$repository = $this->getDoctrine ()->getRepository ( "HBBlogBundle:Article" );
			
		// On demande au repository tous les articles
		$articles = $repository->findAll ();
		
		if ($articles == null) {
			throw new \SoapFault("Receiver", "No article found");
		}
		
		// On transmet nos articles à la vue
		return $articles;
	}

	/**
	 * Affiche un article sur un id
	 *
	 * @Soap\Method("getArticle")
	 * @Soap\Param("id", phpType = "int")
	 * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article")
	 */
	public function getArticleAction($id) {
		// on récupére l'article 
		$repo = $this->getDoctrine()->getRepository("HBBlogBundle:Article");
		$article = $repo->find($id);
		// On transmet l'article
		if ($article == null) {
			throw new \SoapFault("Sender", "No article found");
		}
		return $article;
	}

	/**
	 * Crée ou modifie un article
	 *
	 * @Soap\Method("editArticle")
	 * @Soap\Param("article", phpType = "HB\BlogBundle\Entity\Article")
	 * @Soap\Result(phpType = "boolean")
	 */
	public function editArticleAction(Article $article) {
		// on a récupéré l'article grace à un ParamConverter magique
		
		if ($article == null) {
			throw new \SoapFault("Sender", "Invalid data");
		}
		
		// On regarde si on a un article existant (add/edit)
		$em = $this->getDoctrine()->getManager();
		
		//Problème de persistance automatique, utilisation de merge...
		if ($article->getId()>0) {
			$oldArticle = $em->find("HBBlogBundle:Article", $article->getId());
			$article->setDatecreation($oldArticle->getDatecreation());
		} else {
			if ($article->getDatecreation()==null) {
				$article->setDatecreation(new \DateTime());
			}
		}
		
		// On utilise merge et non persist car l'objet Article ne vient pas de 
		// l'entitymanager mais est instancié à partir de SoapBundle
		$em->merge($article);
		$em->flush();
		
		// On renvoie le résultat, plus de test à faire (normalement)
		return true ;
		//return $this->getIndexArticlesAction();
	}
	
	/**
	 * Supprime un article sur un id
	 *
	 * @Soap\Method("deleteArticle")
	 * @Soap\Param("id", phpType = "int")
	 * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article[]")
	 */
	public function deleteArticleAction($id) {
		
		// on récupére l'article
		$repo = $this->getDoctrine()->getRepository("HBBlogBundle:Article");
		$article = $repo->find($id);
		
		if ($article == null) {
			throw new \SoapFault("Sender", "Invalid data");
		}
		
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($article);
		$em->flush();
		
		
		// on redirige vers la liste des articles
		return $this->getIndexArticlesAction();		
	}
}
