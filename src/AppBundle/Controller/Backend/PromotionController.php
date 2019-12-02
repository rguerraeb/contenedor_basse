<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionType;
use AppBundle\Repository\EbClosion;

/**
 * Promotion controller.
 */
class PromotionController extends Controller {
    
    private $moduleId = 9;

	/**
	 * @Route("/backend/promotion/{campaignId}", name="backend_promotion")
	 */
	public function indexAction(Request $request) {

		$md5Id = $request->get ( "campaignId" );
		$campaignId = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneByMd5Id ( $md5Id );
		$campaign = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneBy ( array (
				"campaignId" => $campaignId 
		) );

		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$promotion = new Promotion();
		$form = $this->createForm ( new PromotionType(), $promotion );
		$form->handleRequest ( $request );
		$userData = $this->get ( "session" )->get ( "userData" );
		
		// Validar formulario
		if ($form->isSubmitted ()) {
			if ($form->isValid ()) {
				
				$name = $form->get('name')->getData();
				$promoCode = $form->get('promoCode')->getData();
				$createdBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
					"id" => $this->getUser()->getId()
				) );

				$promotionExist = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->getPromotionExist($name, $promoCode, $campaign->getCampaignId());

				if (!$promotionExist) {
					$file = $promotion->getImageFile();
					// Generate a unique name for the file before saving it
					$fileName = md5(uniqid()).'.'.$file->guessExtension();
					// Move the file to the directory where brochures are stored
					$file->move(
						$this->getParameter('promotion_image_path'),
						$fileName
					);
					// save
					$promotion->setImagePath($fileName);
					$promotion->setCampaign($campaign);
					$promotion->setCreatedAt(new \DateTime());
					$promotion->setCreatedBy($createdBy);
					$em = $this->getDoctrine ()->getManager ();
					$em->persist ( $promotion );
					$em->flush ();
					
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
					return $this->redirectToRoute ( "backend_promotion", array("campaignId" => $md5Id) );
				} else {
					$this->addFlash ( 'error_message', $this->getParameter ( 'error_promotion_exist' ) );
				}
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
			}
		}
		
		$query = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->findBy(
			array(
				"campaign" => $campaign
			)
		);

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	
        
		return $this->render ( '@App/Backend/Promotion/index.html.twig', array (
				"form" => $form->createView (),
		        "list" => $query,
				"permits" => $mp,
				"campaignName" => $campaign->getName()				
		) );
	}

	/**
	 * @Route("/backend/promotion/edit/{campaignId}/{promotionId}", name="backend_promotion_edit")
	 */
	public function editAction(Request $request) {
		$md5Id = $request->get ( "campaignId" );
		$campaignId = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneByMd5Id ( $md5Id );
		$campaign = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneBy ( array (
				"campaignId" => $campaignId 
		) );

		$md5PromotionId = $request->get("promotionId");
		$promotionId = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->findOneByMd5Id ( $md5PromotionId );
		$promotion = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->findOneBy ( array (
			"promotionId" => $promotionId 
		) );
		
		if ($promotion) {

			$oldImage = $promotion->getImagePath();

			$form = $this->createForm ( new PromotionType(), $promotion );
			$form->handleRequest ( $request );
			
			if ($form->isSubmitted ()) {
				if ($form->isValid ()) {
					
					$userData = $this->get ( "session" )->get ( "userData" );				
					$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
						"id" => $this->getUser()->getId()
					) );

					$file = $promotion->getImageFile();

					if ($file) {
						// Generate a unique name for the file before saving it
						$fileName = md5(uniqid()).'.'.$file->guessExtension();
						// Move the file to the directory where brochures are stored
						$file->move(
							$this->getParameter('promotion_image_path'),
							$fileName
						);
						$promotion->setImagePath($fileName);
					} else {
						$promotion->setImagePath($oldImage);
					}

					$promotion->setCampaign($campaign);
					$promotion->setUpdatedAt( new \DateTime () );
					$promotion->setUpdatedBy($updatedBy);
					$em = $this->getDoctrine()->getManager ();
					$em->persist ( $promotion );
					$em->flush ();
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
					return $this->redirectToRoute ( "backend_promotion_edit", ["campaignId" => $md5Id, "promotionId" => $md5PromotionId]);
				} else {
					$this->addFlash ( 'alert_message', $this->getParameter ( 'error_form' ) );									
				}
			}
			return $this->render ( '@App/Backend/Promotion/edit.html.twig', array (
					"form" => $form->createView (),
					"campaignId" => $md5Id,
					"campaignName" => $campaign->getName()
			));
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
		}
		return $this->redirectToRoute ( "backend_promotion", ["campaignId" => $md5Id] );
	}

	/**
	 * @Route("/backend/promotion/delete/{campaignId}/{promotionId}", name="backend_promotion_delete")
	 */
	public function deleteAction(Request $request) {
		$md5Id = $request->get ( "campaignId" );
		$campaignId = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneByMd5Id ( $md5Id );
		$campaign = $this->getDoctrine ()->getRepository ( 'AppBundle:Campaign' )->findOneBy ( array (
				"campaignId" => $campaignId 
		) );

		$md5PromotionId = $request->get("promotionId");
		$promotionId = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->findOneByMd5Id ( $md5PromotionId );
		$promotion = $this->getDoctrine ()->getRepository ( 'AppBundle:Promotion' )->findOneBy ( array (
			"promotionId" => $promotionId 
		) );

		if ($promotion) {
			$em = $this->getDoctrine ()->getManager ();
            // Eliminar
            $updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
				"id" => $this->getUser()->getId()
			) );
					
			$promotion->setUpdatedAt ( new \DateTime () );
			$promotion->setUpdatedBy ($updatedBy);
			$promotion->setStatus('INACTIVO');
			$em->persist( $promotion );
			$em->flush ();
			
			$this->addFlash ( 'success_message', $this->getParameter ( 'exito_inactivar' ) );
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_promotion", ["campaignId" => $md5Id] );
	}
}
