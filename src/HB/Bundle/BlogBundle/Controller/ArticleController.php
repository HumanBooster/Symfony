<?php

namespace HB\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HB\Bundle\BlogBundle\Entity\Article;
use HB\Bundle\BlogBundle\Form\ArticleType;

/**
 * ArticleController
 *
 * Controleur permettant de faire un CRUD sur les articles
 *
 * @Route("/article")
 */
class ArticleController extends Controller {
	/**
	 * List of articles
	 *
	 * @Route("/", name="article_list")
	 * @Template("HBBlogBundle:Article:list.html.twig")
	 */
	public function indexAction() {
		$articles = $this->getDoctrine()
						->getEntityManager()
						->getRepository("HBBlogBundle:Article")
						->findAll();
		return array (
				'articles' => $articles 
		);
	}
	
	/**
	 * Add a new article
	 *
	 * @Route("/add", name="article_add")
	 * @Template("HBBlogBundle:Article:edit.html.twig")
	 */
	public function addAction() {
		return $this->addEditForm(new Article());
	}
	
	/**
	 * Edit an article
	 *
	 * @Route("/edit/{id}", name="article_edit")
	 * @Template("HBBlogBundle:Article:edit.html.twig")
	 */
	public function editAction(Article $article) {
		return $this->addEditForm($article);
	}
	
	/**
	 * Private function to show form for Add and Edit actions
	 * 
	 * @param Article $article
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|multitype:\Symfony\Component\Form\FormView
	 */
	private function addEditForm(Article $article) {

		$form = $this->createForm(new ArticleType(), $article);
		
		// On récupère la requête
		$request = $this->get('request');
		
		// On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
		if ($request->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $article contient les valeurs entrées dans
			// le formulaire par le visiteur
			$form->bind($request);
			// On vérifie que les valeurs entrées sont correctes
			// (Nous verrons la validation des objets en détail dans le prochain chapitre)
			if ($form->isValid()) {
				// On l'enregistre notre objet $article dans la base de données
				$em = $this->getDoctrine()->getManager();
				$em->persist($article);
				$em->flush();
		
				// On redirige vers la page de visualisation de l'article nouvellement créé
				return $this->redirect($this->generateUrl('article_read', array('id' => $article->getId())));
			}
		}
		
		// On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher
		// le formulaire toute seule, on a d'autres méthodes si on veut personnaliser
		return array( 'form' => $form->createView() );
	}

	/**
	 * Read/view an article
	 *
	 * @Route("/read/{id}", name="article_read")
	 * Template("HBBlogBundle:Article:read.html.twig")
	 * @Template()
	 */
	public function readAction(Article $article) {
		/*$article = $this->getDoctrine ()
		->getRepository ( 'HBBlogBundle:Article' )
		->find ( $id );
		
		if (! $article) {
			throw $this->createNotFoundException ( 'Aucun article trouvé pour cet id : ' . $id );
		}*/
		
		return array("article" => $article);
	}
	
	/**
	 * Delete an article
	 *
	 * @Route("/delete/{id}", name="article_delete")
	 */
	public function deleteAction(Article $article) {
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($article);
		$em->flush();
		
		return $this->redirect($this->generateUrl('article_list'));
	}
}
