<?php

namespace HB\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HB\Bundle\BlogBundle\Entity\Auteur;
use HB\Bundle\BlogBundle\Form\AuteurType;

/**
 * AuteurController
 *
 * Controleur permettant de faire un CRUD sur les auteurs
 *
 * @Route("/auteur")
 */
class AuteurController extends Controller {
	/**
	 * List of auteurs
	 *
	 * @Route("/", name="auteur_list")
	 * @Template("HBBlogBundle:Auteur:list.html.twig")
	 */
	public function indexAction() {
		$auteurs = $this->getDoctrine()
						->getEntityManager()
						->getRepository("HBBlogBundle:Auteur")
						->findAll();
		return array (
				'auteurs' => $auteurs 
		);
	}
	
	/**
	 * Add a new auteur
	 *
	 * @Route("/add", name="auteur_add")
	 * @Template("HBBlogBundle:Auteur:edit.html.twig")
	 */
	public function addAction() {
		return $this->addEditForm(new Auteur());
	}
	
	/**
	 * Edit an auteur
	 *
	 * @Route("/edit/{id}", name="auteur_edit")
	 * @Template("HBBlogBundle:Auteur:edit.html.twig")
	 */
	public function editAction(Auteur $auteur) {
		return $this->addEditForm($auteur);
	}
	
	/**
	 * Private function to show form for Add and Edit actions
	 * 
	 * @param Auteur $auteur
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|multitype:\Symfony\Component\Form\FormView
	 */
	private function addEditForm(Auteur $auteur) {

		$form = $this->createForm(new AuteurType(), $auteur);
		
		// On récupère la requête
		$request = $this->get('request');
		
		// On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
		if ($request->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			// À partir de maintenant, la variable $auteur contient les valeurs entrées dans
			// le formulaire par le visiteur
			$form->bind($request);
			// On vérifie que les valeurs entrées sont correctes
			// (Nous verrons la validation des objets en détail dans le prochain chapitre)
			if ($form->isValid()) {
				// On l'enregistre notre objet $auteur dans la base de données
				$em = $this->getDoctrine()->getManager();
				$em->persist($auteur);
				$em->flush();
		
				// On redirige vers la page de visualisation de l'auteur nouvellement créé
				return $this->redirect($this->generateUrl('auteur_read', array('id' => $auteur->getId())));
			}
		}
		
		// On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher
		// le formulaire toute seule, on a d'autres méthodes si on veut personnaliser
		return array( 'form' => $form->createView() );
	}

	/**
	 * Read/view an auteur
	 *
	 * @Route("/read/{id}", name="auteur_read")
	 * Template("HBBlogBundle:Auteur:read.html.twig")
	 * @Template()
	 */
	public function readAction(Auteur $auteur) {
		/*$auteur = $this->getDoctrine ()
		->getRepository ( 'HBBlogBundle:Auteur' )
		->find ( $id );
		
		if (! $auteur) {
			throw $this->createNotFoundException ( 'Aucun auteur trouvé pour cet id : ' . $id );
		}*/
		
		return array("auteur" => $auteur);
	}
	
	/**
	 * Delete an auteur
	 *
	 * @Route("/delete/{id}", name="auteur_delete")
	 */
	public function deleteAction(Auteur $auteur) {
		$em = $this->getDoctrine()->getEntityManager();
		$em->remove($auteur);
		$em->flush();
		
		return $this->redirect($this->generateUrl('auteur_list'));
	}
}
