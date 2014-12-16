<?php

namespace HB\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use HB\Bundle\BlogBundle\Entity\Commentaire;
use HB\Bundle\BlogBundle\Entity\Article;
use HB\Bundle\BlogBundle\Form\CommentaireType;

/**
 * CommentaireController
 *
 * Controleur permettant de faire un CRUD sur les commentaires
 *
 * @Route("/commentaire")
 */
class CommentaireController extends Controller {
	/**
	 * List of commentaires
	 *
	 * @Route("/", name="commentaire_list")
	 * @Template("HBBlogBundle:Commentaire:list.html.twig")
	 */
	public function indexAction() {
		$commentaires = $this->getDoctrine()
						->getEntityManager()
						->getRepository("HBBlogBundle:Commentaire")
						->findAll();
		return array (
				'commentaires' => $commentaires 
		);
	}
	
	/**
	 * Add a new commentaire
	 *
	 * @Route("/add", name="commentaire_add")
	 * @Template("HBBlogBundle:Commentaire:edit.html.twig")
	 */
	public function addAction() {
		return $this->addEditForm(new Commentaire());
	}
	
	/**
	 * Add a new commentaire
	 *
	 * @Route("/add/{article_id}", name="commentaire_add_to_article")
	 * @Template("HBBlogBundle:Commentaire:edit.html.twig")
	 * @ParamConverter("article", class="HBBlogBundle:Article", options={"id" = "article_id"})
	 */
	public function addToArticleAction(Article $article,  $originalRequest = null) {
		$commentaire = new Commentaire();
		$commentaire->setArticle($article);
		return $this->addEditForm($commentaire,  $originalRequest);
	}
	
	/**
	 * Edit an commentaire
	 *
	 * @Route("/edit/{id}", name="commentaire_edit")
	 * @Template("HBBlogBundle:Commentaire:edit.html.twig")
	 */
	public function editAction(Commentaire $commentaire) {
		return $this->addEditForm($commentaire);
	}
	
	/**
	 * Private function to show form for Add and Edit actions
	 * 
	 * @param Commentaire $commentaire
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|multitype:\Symfony\Component\Form\FormView
	 */
	private function addEditForm(Commentaire $commentaire,  $originalRequest = null) {

		// on force l'user courant
		$commentaire->setAuteur($this->getUser());
		
		$form = $this->createForm(new CommentaireType(), $commentaire);
		
		// On récupère la requête
		if ($originalRequest!=null) {
			$request = $originalRequest;
		} else {
			$request = $this->get('request');
		}
		// best dump ever
		//\Doctrine\Common\Util\Debug::dump($originalRequest);
		
		
		// On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
		if ($request->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $commentaire contient les valeurs entrées dans
			// le formulaire par le visiteur
			$form->bind($request);
			// On vérifie que les valeurs entrées sont correctes
			// (Nous verrons la validation des objets en détail dans le prochain chapitre)
			if ($form->isValid()) {
				// On l'enregistre notre objet $commentaire dans la base de données
				$em = $this->getDoctrine()->getManager();
				$em->persist($commentaire);
				$em->flush();
				
				if ($originalRequest!=null)// on retourne un nouveau formulaire vide si on vient de article
					return array('form'=> $this->createForm(new CommentaireType(), new Commentaire())->createView());
				else
					return $this->redirect($this->generateUrl("article_read", array('id' => $commentaire->getArticle()->getId())));
			}
			
		}
		
		// On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher
		// le formulaire toute seule, on a d'autres méthodes si on veut personnaliser
		return array( 'form' => $form->createView() );
	}

	/**
	 * Read/view an commentaire
	 *
	 * @Route("/read/{id}", name="commentaire_read")
	 * Template("HBBlogBundle:Commentaire:read.html.twig")
	 * @Template()
	 */
	public function readAction(Commentaire $commentaire) {
		/*$commentaire = $this->getDoctrine ()
		->getRepository ( 'HBBlogBundle:Commentaire' )
		->find ( $id );
		
		if (! $commentaire) {
			throw $this->createNotFoundException ( 'Aucun commentaire trouvé pour cet id : ' . $id );
		}*/
		
		return array("commentaire" => $commentaire);
	}
	
	/**
	 * Delete an commentaire
	 *
	 * @Route("/delete/{id}", name="commentaire_delete")
	 */
	public function deleteAction(Commentaire $commentaire) {
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($commentaire);
		$em->flush();
		
		return $this->redirect($this->generateUrl('commentaire_list'));
	}
}
