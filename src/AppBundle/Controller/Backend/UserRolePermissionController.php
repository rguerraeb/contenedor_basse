<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\UserRole;
use AppBundle\Form\UserRoleType;
use AppBundle\Entity\UserRoleModulePermission;
use AppBundle\Form\UserRoleModulePermissionType;

/**
 * UserRole controller.
 */
class UserRolePermissionController extends Controller {

	/**
	 * @Route("/backend/user-role-permission/{roleId}", name="backend_user_role_permission")
	 */
	public function indexAction(Request $request) {
		
		$md5Id = $request->get ( "roleId" );
		$roleId = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneByMd5Id ( $md5Id );
		$role = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRole' )->findOneBy ( array (
				"id" => $roleId
		) );
		
		
		$userRoleMp = new UserRoleModulePermission();
		$form = $this->createForm ( new UserRoleModulePermissionType(), $userRoleMp );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
				) );
				// save
				$userRoleMp->setCreatedAt(new \DateTime());
				$userRoleMp->setCreatedBy($createdBy);
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $userRoleMp );
				$em->flush ();
				
				$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
				return $this->redirectToRoute ( "backend_user_role_permission", array('roleId' => $md5Id) );
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRoleModulePermission' )->findBy (array(
				"userRole" => $role
		));
		
		
		return $this->render ( '@App/Backend/UserRolePermission/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query,	
				"roleId" => $md5Id,
				"role" => $role
		) );
	}

	/**
	 * @Route("/backend/user-role-permission/edit/{roleId}/{rolePmId}", name="backend_user_role_permission_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "rolePmId" );
		$md5RoleId = $request->get ( "roleId" );
		$rolePmId = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRoleModulePermission' )->findOneByMd5Id ( $md5Id );
		$rolePm = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRoleModulePermission' )->findOneBy ( array (
				"id" => $rolePmId 
		) );
		
		if ($rolePm) {
			
			$form = $this->createForm ( new UserRoleModulePermissionType (), $rolePm );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );
					$user = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
							"id" => $this->getUser()->getId()
					) );
					
					$rolePm->setUpdatedAt ( new \DateTime () );
					$rolePm->setUpdatedBy ($user);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $rolePm );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
				} else {
					return $this->render ( '@App/Backend/UserRole/edit.html.twig', array (
							"form" => $form->createView (),
							"roleId" => $md5RoleId
					)
					 );
				}
			} else {
				
				return $this->render ( '@App/Backend/UserRolePermission/edit.html.twig', array (
						"form" => $form->createView (),
						"roleId" => $md5RoleId
				) );
			}
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_user_role_permission", array("roleId" => $md5RoleId) );
	}

	/**
	 * @Route("/backend/user-role-permission/delete/{roleId}/{rolePmId}", name="backend_user_role_permission_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "rolePmId" );
		$md5RoleId = $request->get ( "roleId" );
		$rolePmId = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRoleModulePermission' )->findOneByMd5Id ( $md5Id );
		$rolePm = $this->getDoctrine ()->getRepository ( 'AppBundle:UserRoleModulePermission' )->findOneBy ( array (
				"id" => $rolePmId 
		) );
		if ($rolePm) {
			$em = $this->getDoctrine ()->getManager ();
			// Eliminar
			$em->remove ( $rolePm );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
		}
		
		return $this->redirectToRoute ( "backend_user_role_permission", array('roleId' => $md5RoleId) );
	}
}
