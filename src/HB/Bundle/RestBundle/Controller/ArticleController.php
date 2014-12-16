<?php

namespace HB\Bundle\RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use HB\Bundle\BlogBundle\Entity\Article;
use HB\Bundle\BlogBundle\Form\ArticleRestType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * RestArticleController
 *
 * Controleur permettant de faire un CRUD sur les articles à travers un WS Rest
 */
class ArticleController extends FOSRestController implements ClassResourceInterface {
	
	/**
	 * Collection get action
	 * @var Request $request
	 * @return array
	 * 
     * @ApiDoc(
     *  resource=true,
     *  description="Cette méthode permet de récupérer la liste des articles"
     * )
	 *
	 * @Rest\View()
	 */
	public function cgetAction(Request $request)
	{
		$articles = $this->getDoctrine()
						->getEntityManager()
						->getRepository("HBBlogBundle:Article")
						->findAll();
		return array (
				'entities' => $articles
		);
	}
	
	/**
	 * Get action
	 * @var integer $id Id of the entity
	 * @return array
	 * 
     * @ApiDoc(
     *  resource=true,
     *  description="Cette méthode permet de récupérer un article en fonction de son id",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Article id"}
     *  }
     * )
	 *
	 * @Rest\View()
	 */
	public function getAction($id)
	{
		$article = $this->getEntity($id);
		return array(
				'entity' => $article
		);
	}

	/**
	 * Collection post action
	 * 
	 * @ApiDoc()
	 * 
	 * @var Request $request
	 * @return View|array
	 */
	public function cpostAction(Request $request)
	{
		$article = new Article();
		$form = $this->createForm(new ArticleRestType(), $article);
		$form->bind($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();
			return $this->redirectView(
					$this->generateUrl(
							'get_article',
							array('id' => $article->getId())
					),
					Codes::HTTP_CREATED
			);
		}
		return array(
				'form' => $form,
		);
	}
	/**
	 * Put action
	 * 
	 * @ApiDoc()
	 * 
	 * @var Request $request
	 * @var integer $id Id of the entity
	 * @return View|array
	 */
	public function putAction(Request $request, $id)
	{
		$article = $this->getEntity($id);
		$form = $this->createForm(new RestArticleType(), $article);
		$form->bind($request);
		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($article);
			$em->flush();
			return $this->view(null, Codes::HTTP_NO_CONTENT);
		}
		return array(
				'form' => $form,
		);
	}
	/**
	 * Delete action
	 * 
	 * @ApiDoc()
	 * 
	 * @var integer $id Id of the entity
	 * @return View
	 */
	public function deleteAction($id)
	{
		$article = $this->getEntity($id);
		$em = $this->getDoctrine()->getManager();
		$em->remove($article);
		$em->flush();
		return $this->view(null, Codes::HTTP_NO_CONTENT);
	}
	/**
	 * Get entity instance
	 * 
	 * @ApiDoc()
	 * 
	 * @var integer $id Id of the entity
	 * @return Article
	 */
	protected function getEntity($id)
	{
		$em = $this->getDoctrine()->getManager();
		$article = $em->getRepository("HBBlogBundle:Article")->find($id);
		if (!$article) {
			throw $this->createNotFoundException('Unable to find article entity');
		}
		return $article;
	}
}
