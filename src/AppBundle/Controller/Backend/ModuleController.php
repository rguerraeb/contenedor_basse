<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\ModuleCatalog;
use AppBundle\Form\ModuleCatalogType;

/**
 * UserRole controller.
 */
class ModuleController extends Controller {
	

	/**
	 * @Route("/backend/modules", name="backend_modules")
	 */
	public function indexAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", 10 );
		$module = new ModuleCatalog();
		$form = $this->createForm ( new ModuleCatalogType(), $module );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
				) );
				
				// save
				$module->setCreatedAt(new \DateTime());
				$module->setCreatedBy($createdBy);
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $module );
				$em->flush ();
				
				$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
				return $this->redirectToRoute ( "backend_modules" );
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:ModuleCatalog' )->getList ();

		
		return $this->render ( '@App/Backend/Modules/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query->execute(),				
		) );
	}

	/**
	 * @Route("/backend/modules/{moduleId}", name="backend_modules_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "moduleId" );
		$moduleId = $this->getDoctrine ()->getRepository ( 'AppBundle:ModuleCatalog' )->findOneByMd5Id ( $md5Id );
		$module = $this->getDoctrine ()->getRepository ( 'AppBundle:ModuleCatalog' )->findOneBy ( array (
				"id" => $moduleId 
		) );
		
		if ($module) {
			
			$form = $this->createForm ( new ModuleCatalogType(), $module );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );				
					$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
							"id" => $this->getUser()->getId()
					) );
					
					$module->setUpdatedAt ( new \DateTime () );
					$module->setUpdatedBy ($updatedBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $module );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
					return $this->redirectToRoute ( "backend_modules");
				} else {
					$this->addFlash ( 'alert_message', $this->getParameter ( 'error_form' ) );									
				}
			}
			return $this->render ( '@App/Backend/Modules/edit.html.twig', array (
					"form" => $form->createView ()
			));
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_modules" );
	}

	/**
	 * @Route("/backend/modules/delete/{moduleId}", name="backend_modules_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "moduleId" );
		$moduleId = $this->getDoctrine ()->getRepository ( 'AppBundle:ModuleCatalog' )->findOneByMd5Id ( $md5Id );
		$module = $this->getDoctrine ()->getRepository ( 'AppBundle:ModuleCatalog' )->findOneBy ( array (
				"id" => $moduleId 
		) );
		if ($module) {
			$em = $this->getDoctrine ()->getManager ();
			// Eliminar
			$em->remove ( $module );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
		}
		
		return $this->redirectToRoute ( "backend_modules" );
	}
}
