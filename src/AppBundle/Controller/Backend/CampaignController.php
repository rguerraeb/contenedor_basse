<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Campaign;
use AppBundle\Form\CampaignType;
use AppBundle\Repository\EbClosion;

/**
 * Campaign controller.
 */
class CampaignController extends Controller {
    
    private $moduleId = 13;

	/**
	 * @Route("/backend/campaign", name="backend_campaign")
	 */
	public function indexAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$campaign = new Campaign();
		$form = $this->createForm ( new CampaignType(), $campaign );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				
				$name = $form->get('name')->getData();
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
					"id" => $this->getUser()->getId()
				) );

				$campaignExist = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->getCampaignExist($name);
				
				if (!$campaignExist) {
					// save
					
					$campaign->setCreatedAt(new \DateTime());
					$campaign->setCreatedBy($createdBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $campaign );
					$em->flush ();
					
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
					return $this->redirectToRoute ( "backend_campaign" );
				} else {
					$this->addFlash ( 'error_message', $this->getParameter ( 'error_campaign_exist' ) );
				}
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findAll();

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	
        
		return $this->render ( '@App/Backend/Campaign/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query,
		        "permits" => $mp				
		) );
	}

	/**
	 * @Route("/backend/campaign/edit/{campaignId}", name="backend_campaign_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "campaignId" );
		$campaignId = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneByMd5Id ( $md5Id );
		$campaign = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneBy ( array (
				"campaignId" => $campaignId 
		) );
		
		if ($campaign) {
			
			$form = $this->createForm ( new CampaignType(), $campaign );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );				
					$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
					) );

					$campaign->setUpdatedAt ( new \DateTime () );
					$campaign->setUpdatedBy ($updatedBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $campaign );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
					return $this->redirectToRoute ( "backend_campaign_edit", ["campaignId" => $md5Id]);
				} else {
					$this->addFlash ( 'alert_message', $this->getParameter ( 'error_form' ) );									
				}
			}
			return $this->render ( '@App/Backend/Campaign/edit.html.twig', array (
					"form" => $form->createView ()
			));
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_campaign" );
	}

	/**
	 * @Route("/backend/campaign/delete/{campaignId}", name="backend_campaign_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "campaignId" );
		$campaignId = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneByMd5Id ( $md5Id );
		$campaign = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneBy ( array (
				"campaignId" => $campaignId 
		) );
		if ($campaign) {
			$em = $this->getDoctrine ()->getManager ();
            // Eliminar
            $updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
				"id" => $this->getUser()->getId()
			) );
					
			$campaign->setUpdatedAt ( new \DateTime () );
			$campaign->setUpdatedBy ($updatedBy);
			$campaign->setStatus('INACTIVO');
			$em->persist( $campaign );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_inactivar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_campaign" );
	}
}
