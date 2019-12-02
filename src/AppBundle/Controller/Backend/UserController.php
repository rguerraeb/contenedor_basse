<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Helper\UserHelper;
use AppBundle\Repository\EbClosion;

class UserController extends Controller {
	
    
    private $moduleId = 3;
    
    /**
     * @Route("/backend/user", name="backend_user")
     */
	public function indexAction(Request $request) {
		
		$this->get("session")->set("module_id", $this->moduleId);		
		
		$user = new User ();
		$form = $this->createForm ( new UserType (), $user );
		$form->handleRequest ( $request );
		$em = $this->getDoctrine()->getManager();
		$userData = $this->get ( "session" )->get ( "userData" );
		$user_id = $this->getUser()->getId();
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				// Encode password
				$error = $this->validateNewUser($user);

				if ($error) {
					// Add error flash
					$this->addFlash('error_message', $error);
				}
				else {
					$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
					) );
					$user = $this->create($user, $createdBy);
					
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
					return $this->redirectToRoute ( "backend_user" );
				}
			}
			else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
            
		$queryUser = $em->getRepository ( 'AppBundle:User' )->getList ();
		//$paginator = $this->get ( 'knp_paginator' );
		
		//$pagination = $paginator->paginate ( $queryUser, $request->query->getInt ( 'page', 1 ), $this->getParameter ( "number_of_rows" ) );
		
		$mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));		
		
		return $this->render ( '@App/Backend/User/index.html.twig', array (
				"form" => $form->createView (),
				"list" => $queryUser,
				"action" => "backend_user_index",
		        "permits" => $mp
		) );
	}
	
	/**
	 * @Route("/backend/user/edit/{id}", name="backend_user_edit", requirements={"id": "\d+"})
	 */
	public function editAction(Request $request, User $user) {
		$oldPassword = $user->getPassword ();
		$oldEmail = $user->getEmail ();
		$em = $this->getDoctrine()->getManager();
		$userData = $this->get ( "session" )->get ( "userData" );
		$user_id = $this->getUser()->getId();

		if ($user) {
			$form = $this->createForm ( new UserType (), $user );
			$form->handleRequest ( $request );
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					$error = $this->validateEditUser($user, $oldEmail);

					if ($error) {
						$this->addFlash('error_message', $error);
					}
					else {		
						
						$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
							"id" => $this->getUser()->getId()
						) );
						
						$user = $this->edit($user, $oldPassword, $createdBy);
					}

					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
				} else {
					// Error validation
					$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
				}
			}

			return $this->render ( '@App/Backend/User/edit.html.twig', array(
					"form" => $form->createView (),
					"edit" => true
			) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_user" );
	}
	
	/**
	 * @Route("/backend/user/delete/{id}", name="backend_user_delete", requirements={"id": "\d+"})
	 */
	public function deleteAction(Request $request, User $user) {
		if ($user) {
			// Can't delete yourself
			if ($user->getId() != $this->getUser()->getId()) {
				$em = $this->getDoctrine ()->getManager ();

				// Make inactive
				$user->setStatus('INACTIVO');
				$em->persist( $user );
				$em->flush ();
				
				$this->addFlash ( 'success_message', $this->getParameter ( 'exito_inactivar' ) );
			}
			else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
			}
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_user" );
	}

	/**
	 * Encodes password, sets created at and by and stores in db
	 * 
	 * @param User $user the entity to be created
	 * @param User $creator the user who creates a user
	 * @return User user saved in db
	 */
	public function create($user, $creator) {
		$em = $this->getDoctrine()->getManager();

	    // Encode password
	    $userHelper = $this->get('user.helper');
	    $user->setPassword($userHelper->encodePassword($user, $user->getPassword()));

	    // Set created By
	    $user->setCreatedBy($creator);

	    // Set created At
	    $user->setCreatedAt(new \DateTime());

	    // Save in database
	    $em->persist($user);
	    $em->flush();

	    return $user;
	}

	/**
	 * Validates the information of a new user
	 *
	 * @param User $user possible new user
	 * @return string error message. NULL if no error
	 */
	public function validateNewUser($user) {
		$em = $this->getDoctrine()->getManager();

	    // Check for duplicate email
	    $original = $em->getRepository('AppBundle:User')->findOneByEmail($user->getEmail());

	    if ($original) {
	        // Email already in database
	        return $this->getParameter('usuario_existente');
	    }

	    return null;
	}

	public function edit($user, $oldPassword, $editor){
		$em = $this->getDoctrine()->getManager();

		// If no password provided, use last password
		if ($user->getPassword() == "") {
			$user->setPassword($oldPassword);
		}
		else {
			// Encode password
			$userHelper = $this->get('user.helper');
			$user->setPassword($userHelper->encodePassword($user, $user->getPassword()));
		}

		// Set updated By
		$user->setUpdatedBy($editor);

		// Set updated at
		$user->setUpdatedAt(new \DateTime());

		// Store in DB
		$em->persist($user);
		$em->flush();

		return $user;
	}

	/**
	 * Validates the information of a editing user
	 *
	 * @param User $user user to edit
	 * @param string $oldEmail the email before editing
	 * @return string error message. NULL if no error
	 */
	public function validateEditUser($user, $oldEmail) {
		$em = $this->getDoctrine()->getManager();

	    // Check for duplicate email
	    $original = null;
	    if ($user->getEmail() != $oldEmail) {
	    	// Look if duplicate
		    $original = $em->getRepository('AppBundle:User')->findOneByEmail($user->getEmail());
	    }

	    if ($original) {
	        // Email already in database
	        return $this->getParameter('usuario_existente');
	    }

	    return null;
	}
}
