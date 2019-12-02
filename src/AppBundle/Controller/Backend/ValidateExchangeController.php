<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\EbClosion;
use AppBundle\Entity\StaffPromotion;
use AppBundle\Entity\StaffCode;

/**
 * ValidateExchange controller.
 */
class ValidateExchangeController extends Controller {
    
    private $moduleId = 16;

	/**
	 * @Route("/backend/validate-exchange", name="backend_validate_exchange")
	 */
	public function indexAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$userData = $this->get ( "session" )->get ( "userData" );
		$userId = $this->getUser()->getId();
		
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	
        
		return $this->render ( '@App/Backend/ValidateExchange/index.html.twig', array (
		        "permits" => $mp				
		) );
	}

	/**
	 * @Route("/backend/validate-exchange-result", name="backend_validate_exchange_result")
	 */
	public function indexResultAction(Request $request) {
		$code = $request->get ( "code" );
		$phone = $request->get ( "phone" );

		$userData = $this->get ( "session" )->get ( "userData" );
		
		$userId = $this->getUser()->getId();

		$response = new JsonResponse();

		$staff = $this->getDoctrine()->getRepository('AppBundle:Staff')->findOneBy( array (
			"phone" => $phone
		));


		$user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy( array (
			"id" => $userId
		));

		if (!$staff) {
			$responseType = 1;
			$codeValue = null;
			$codeId = null;
			$codeStatus = null;
		} else {
			$staffCode = $this->getDoctrine()->getRepository('AppBundle:StaffCode')->findOneBy( array (
				"code" => $code,
				"staff" => $staff
			) );

			if (!$staffCode) {
				$responseType = 2;
				$codeValue = null;
				$codeId = null;
				$codeStatus = null;
				$prizeName = null;
				$prizeType = null;
			} else {
				$responseType = 0;
				$codeValue = $staffCode->getCode();
				$codeId = $staffCode->getStaffCodeId();
				$codeStatus = $staffCode->getCodeStatus()->getCodeStatusId();
				$prizeName = $staffCode->getPrize()->getName();
				$prizeType = $staffCode->getPrize()->getPrizeType();

			}
		}

		$response->setData( array(
			'status' => 'success',
			'prizeName' => $prizeName,
			'prizeType' => $prizeType,
			'responseType' => $responseType,
			'codeValue' => $codeValue,
			'codeId' => $codeId,
			'userRole' => $user->getUserRole()->getId(),
			'codeStatus' => $codeStatus
		) );
        
        return $response;
	}

	/**
	 * @Route("/backend/validate-exchange-invoice", name="backend_validate_exchange_invoice")
	 */
	public function indexInvoiceAction(Request $request) {
		//$md5Id = $request->get ( "codeId" );
		//$staffCodeId = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneByMd5Id ( $md5Id );
		$staffCodeId = $request->get ( "codeId" );
		$staffCode = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneBy ( array (
				"staffCodeId" => $staffCodeId 
		) );
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$userId = $this->getUser()->getId();
		$userDistributor = $this->getUser()->getDistributor();
		$createdBy = $this->getUser();

		$invoiceNumber = $request->get("invoice_number");
		$responsable = $request->get("responsable");
		$store = $request->get("store");

		if($invoiceNumber){
			if($staffCode->getCodeStatus()->getCodeStatusId() == 2){
				$quantityTaken = array();
				
				$validated = array();

				//foreach($promotions as $promo){
				$staffPromotion = new StaffPromotion();
				//	$quantityTaken[$promo->getPromotionId()] = $request->get("quantity_".$promo->getPromotionId());
		
				//	if($quantityTaken[$promo->getPromotionId()] != 0){

						$staffPromotion->setStaffCode($staffCode);
						$staffPromotion->setStaff($staffCode->getStaff());
						$staffPromotion->setDistributor($userDistributor);
						$staffPromotion->setQuantityTaken("1");
						$staffPromotion->setInvoiceNumber($invoiceNumber);
						$staffPromotion->setCreatedAt(new \DateTime());
						$staffPromotion->setCreatedBy($createdBy);
						$em = $this->getDoctrine ()->getManager ();
						$em->persist ( $staffPromotion );
						$em->flush ();
				//	}

				//	array_push($validated, $request->get("quantity_".$promo->getPromotionId()));

				//}


				$codeStatus = $this->getDoctrine ()->getRepository ( 'AppBundle:CodeStatus' )->findOneBy ( array (
					"codeStatusId" => 3 
				) );

				$staffCode->setResponsable($responsable);
				$staffCode->setCodeStatus($codeStatus);
				$staffCode->setUpdatedAt ( new \DateTime () );
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $staffCode );
				$em->flush ();

				$this->addFlash ( 'success_message', $this->getParameter ( 'exito_canje_promo' ) );
				return $this->redirectToRoute ( "backend_validate_exchange_finiquito", ["codeId" => $staffCode->getStaffCodeId()]);
				
			//die();

			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_canje_promo' ) );
				return $this->redirectToRoute ( "backend_validate_exchange_invoice", ["codeId" => $staffCode->getStaffCodeId()]);
			}
		} //else {
		//	$this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
		//}

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	
        
		return $this->render ( '@App/Backend/ValidateExchange/index_invoice_form.html.twig', array (
				"permits" => $mp,
				"staff" => $staffCode->getStaff(),
				"staffCode" => $staffCode
		) );
	}

	/**
	 * @Route("/backend/validate-exchange-finiquito", name="backend_validate_exchange_finiquito")
	 */
	public function indexExchangeAction(Request $request) {
		//$md5Id = $request->get ( "codeId" );
		//$staffCodeId = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneByMd5Id ( $md5Id );
		$staffCodeId = $request->get ( "codeId" );
		$staffCode = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneBy ( array (
				"staffCodeId" => $staffCodeId 
		) );

		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		$userId = $this->getUser()->getId();

		$staffPromotions = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findBy ( array (
				"staffCode" => $staffCode,
				"staff" => $staffCode->getStaff()
		) );

		$invoice = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findOneBy ( array (
			"staffCode" => $staffCode,
			"staff" => $staffCode->getStaff()
		) );

		$mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

		return $this->render ( '@App/Backend/ValidateExchange/index_show_exchange.html.twig', array (
				"permits" => $mp,
				"staff" => $staffCode->getStaff(),
				"staffCode" => $staffCode,
				"promotions" => $staffPromotions,
				"invoice" => $invoice->getInvoiceNumber()
		) );
	}

}
