<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Distributor;
use AppBundle\Form\DistributorType;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Distributor controller.
 */
class DistributorController extends Controller {
    
    private $moduleId = 12;

	/**
	 * @Route("/backend/distributor", name="backend_distributor")
	 */
	public function indexAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$distributor = new Distributor();
		$form = $this->createForm ( new DistributorType(), $distributor );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				
				$name = $form->get('name')->getData();
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
				) );

				$distributorExist = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->getDistributorExist($name);
				
				if (!$distributorExist) {
					// save
					
					$distributor->setCreatedAt(new \DateTime());
					$distributor->setCreatedBy($createdBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $distributor );
					$em->flush ();
					
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
					return $this->redirectToRoute ( "backend_distributor" );
				} else {
					$this->addFlash ( 'error_message', $this->getParameter ( 'error_distributor_exist' ) );
				}
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->findAll();

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	
        
		return $this->render ( '@App/Backend/Distributor/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query,
		        "permits" => $mp				
		) );
	}

	/**
	 * @Route("/backend/distributor/get-state", name="backend_distributor_get_state")
	 */
	public function getStateAction (Request $request)
	{
	
		$countryId = $request->get("countryId");

		$country = $this->getDoctrine()->getRepository("AppBundle:Country")->findOneBy(array(
			"id" => $countryId
		));

		$state = $this->getDoctrine()->getRepository("AppBundle:State")->findBy(array(
			"country" => $country
		));
		
		$response = array();
		foreach ($state as $item) {
			$response[$item->getStateId()]["id"] = $item->getStateId();
			$response[$item->getStateId()]["name"] = $item->getName();
		}
		return new JsonResponse($response);
	}

	/**
	 * @Route("/backend/distributor/get-city", name="backend_distributor_get_city")
	 */
	public function getCityAction (Request $request)
	{
	
		$stateId = $request->get("stateId");

		$state = $this->getDoctrine()->getRepository("AppBundle:State")->findOneBy(array(
			"stateId" => $stateId
		));

		$city = $this->getDoctrine()->getRepository("AppBundle:City")->findBy(array(
			"state" => $state
		));
		
		$response = array();
		foreach ($city as $item) {
			$response[$item->getId()]["id"] = $item->getId();
			$response[$item->getId()]["name"] = $item->getName();
		}
		return new JsonResponse($response);
	}

	/**
	 * @Route("/backend/distributor/edit/{distributorId}", name="backend_distributor_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "distributorId" );
		$distributorId = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->findOneByMd5Id ( $md5Id );
		$distributor = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->findOneBy ( array (
				"distributorId" => $distributorId 
		) );
		
		if ($distributor) {
			
			$form = $this->createForm ( new DistributorType(), $distributor );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );				
					$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
					) );

					$distributor->setUpdatedAt ( new \DateTime () );
					$distributor->setUpdatedBy ($updatedBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $distributor );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
					return $this->redirectToRoute ( "backend_distributor_edit", ["distributorId" => $md5Id]);
				} else {
					$this->addFlash ( 'alert_message', $this->getParameter ( 'error_form' ) );									
				}
			}
			return $this->render ( '@App/Backend/Distributor/edit.html.twig', array (
					"form" => $form->createView ()
			));
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_distributor" );
	}

	/**
	 * @Route("/backend/distributor/delete/{distributorId}", name="backend_distributor_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "distributorId" );
		$distributorId = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->findOneByMd5Id ( $md5Id );
		$distributor = $this->getDoctrine ()->getRepository ( 'AppBundle:Distributor' )->findOneBy ( array (
				"distributorId" => $distributorId 
		) );
		if ($distributor) {
			$em = $this->getDoctrine ()->getManager ();
            // Eliminar
            $updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
				"id" => $this->getUser()->getId()
			) );
					
			$distributor->setUpdatedAt ( new \DateTime () );
			$distributor->setUpdatedBy ($updatedBy);
			$distributor->setStatus('INACTIVO');
			$em->persist( $distributor );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_inactivar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_distributor" );
	}
}
