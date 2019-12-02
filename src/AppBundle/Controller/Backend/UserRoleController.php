<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\UserRole;
use AppBundle\Form\UserRoleType;

/**
 * UserRole controller.
 */
class UserRoleController extends Controller {
	

	/**
	 * @Route("/backend/user-roles", name="backend_user_roles")
	 */
	public function indexAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", 11 );
		$userRole = new UserRole ();
		$form = $this->createForm ( new UserRoleType (), $userRole );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
					"id" => $this->getUser()->getId()
				) );
				// save
				$userRole->setCreatedAt(new \DateTime());
				$userRole->setCreatedBy($createdBy);
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $userRole );
				$em->flush ();
				
				$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
				return $this->redirectToRoute ( "backend_user_roles" );
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findAll ();
		
		
		return $this->render ( '@App/Backend/UserRole/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query,
				"action" => "backend_user_roles" 
		) );
	}

	/**
	 * @Route("/backend/user-roles/{roleId}", name="backend_user_roles_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "roleId" );
		$roleId = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneByMd5Id ( $md5Id );
		$role = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneBy ( array (
				"id" => $roleId 
		) );
		
		if ($role) {
			
			$form = $this->createForm ( new UserRoleType (), $role );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );				
					$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
					) );	
					
					$role->setUpdatedAt ( new \DateTime () );
					$role->setUpdatedBy ($updatedBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $role );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
				} else {
					return $this->render ( '@App/Backend/UserRole/edit.html.twig', array (
							"form" => $form->createView () 
					)
					 );
				}
			} else {
				
				return $this->render ( '@App/Backend/UserRole/edit.html.twig', array (
						"form" => $form->createView () 
				) );
			}
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_user_roles" );
	}

	/**
	 * @Route("/backend/user-roles/delete/{roleId}", name="backend_user_roles_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "roleId" );
		$roleId = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneByMd5Id ( $md5Id );
		$role = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneBy ( array (
				"id" => $roleId 
		) );
		if ($role) {
			$em = $this->getDoctrine ()->getManager ();
			// Eliminar
			$em->remove ( $role );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
		}
		
		return $this->redirectToRoute ( "backend_user_roles" );
	}
}
