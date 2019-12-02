<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Promotion;
use AppBundle\Form\PromotionType;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Report controller.
 */
class ReportController extends Controller {
        private $moduleId = 14;

        /**
         * @Route("/backend/report/campaings/", name="backend_report_campaings")
         */
        public function indexAction(Request $request) {

                $this->get ( "session" )->set ( "module_id", $this->moduleId );
                $userData = $this->get ( "session" )->get ( "userData" );
                $userId = $this->getUser();
				if($this->getUser()->getUserRole()->getId() == 4)
                {
						$queryCanjes = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->getReportCanjes ('ADMIN');
						
                }else{
                        $distributor = $this->getUser()->getDistributor()->getDistributorId();
                        $queryCanjes = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->getReportCanjes ( $distributor );
                }

                $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));	

                return $this->render ( '@App/Backend/Reports/index.html.twig', array (
                "list" => $queryCanjes,
                "permits" => $mp			
                ) );
        }


	/**
     *
     * @Route("/backend_upload", name="backend_upload")
     */
    public function uploadAction (Request $request)
    {
		$dataFILE = $_FILES;
		$dataPOST = $_POST;
		
		$codeId = $request->get("codeId");
		$userId = $request->get("idUser");
		
		$code = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneBy ( array (
				"staffCodeId" => $codeId 
		) );
		
		$staffPromotion = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findBy ( array (
				"staffCode" => $code
		) );

		if ($staffPromotion){
			
			//$oldImage = $staffPromotion->getInvoiceImg();
			$userData = $this->get( "session" )->get ( "userData" );	
			$updatedBy = $this->getDoctrine ()->getRepository ( 'AppBundle:User' )->findOneBy ( array (
				"id" => $userId
			) );
			
			if ($_FILES) {

				if ( 0 < $_FILES['invoiceImg']['error'] ) {
					$response = array('status'=>'error');							
					return new JsonResponse($response);
				}
				else {
					$pos = strrpos($_FILES['invoiceImg']['name'],".");
					$ext = substr($_FILES['invoiceImg']['name'],$pos +1);
					$fileName = md5(uniqid()).'.'.$ext;
					move_uploaded_file($_FILES['invoiceImg']['tmp_name'], $this->getParameter('report_invoice_path') . $fileName);
					
					foreach ($staffPromotion as $obj) {
						$obj->setInvoiceImg($fileName);
						$obj->setUpdatedAt( new \DateTime () );
						$obj->setUpdatedBy($updatedBy);
						$em = $this->getDoctrine()->getManager ();
						$em->persist ( $obj );
						$em->flush ();
					}
					
				
				}
				
			} else {
				//$staffPromotion->setInvoiceImg($oldImage);
				foreach ($staffPromotion as $obj) {
					$oldImage = $obj->getInvoiceImg();
					$obj->setInvoiceImg($oldImage);
					$obj->setUpdatedAt( new \DateTime () );
					$obj->setUpdatedBy($updatedBy);
					$em = $this->getDoctrine()->getManager ();
					$em->persist ( $obj );
					$em->flush ();
				}
			}

			$response = array('status'=>'success');							
			return new JsonResponse($response);
		} else {
			$response = array('status'=>'error');							
			return new JsonResponse($response);
		}
	}

	/**
	 * @Route("/backend/report/campaings/pdf", name="backend_report_campaings_pdf")
	 */
	public function indexGeneratePDFAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		//$md5Id = $request->get ( "codeId" );
		//$staffCodeId = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneByMd5Id ( $md5Id );
		$staffCodeId = $request->get ( "codeId" );
		
		$this->generatePDF($staffCodeId);
	}

	/**
	 * @Route("/backend/validate-exchange-finiquito-pdf", name="backend_validate_exchange_finiquito_pdf")
	 */
	public function indexExchangePDFAction(Request $request) {
		$this->get ( "session" )->set ( "module_id", $this->moduleId );
		//$md5Id = $request->get ( "codeId" );
		//$staffCodeId = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneByMd5Id ( $md5Id );
		$staffCodeId = $request->get ( "codeId" );
		
		$this->generatePDF($staffCodeId);
	}

	function generatePDF($staffCodeId){

		$staffCode = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneBy ( array (
				"staffCodeId" => $staffCodeId 
		) );

		$staffPromotions = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findBy ( array (
				"staffCode" => $staffCode,
				"staff" => $staffCode->getStaff()
		) );

		$invoice = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findOneBy ( array (
			"staffCode" => $staffCode,
			"staff" => $staffCode->getStaff()
		) );
		$content = $this->render ( '@App/Backend/ValidateExchange/finiquito_pdf.html.twig', array (
				"staff" => $staffCode->getStaff(),
				"staffCode" => $staffCode,
				"promotions" => $staffPromotions,
				"invoice" => $invoice->getInvoiceNumber(),
				"dateCreated" => $invoice->getCreatedAt(),
				"distributor" => $invoice->getDistributor()->getName(),
				"store" => $staffCode->getStore()
		) );

		$html2pdf = new \Html2Pdf();
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content->getContent());
		$html2pdf->output();
	}

	/**
	 * @Route("/backend/report/show/promotion", name="backend_report_show_promotions")
	 */
	public function showPromotionsAction(Request $request) {
		//echo '123456'; 
		$staffCodeId = $request->get("staffCodeId");

		$staffCode = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findOneBy ( array (
			"staffCodeId" => $staffCodeId 
		) );

		$staffPromotions = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffPromotion' )->findBy ( array (
			"staffCode" => $staffCode
		) );
		//var_dump($staffPromotions);
		
		$invoice = '';
		$table = '<table class="table table-styling table-border"> <thead>';
		$table .= '<tr class="text-center table-active"><th>Promoci&oacute;n</th><th>Cantidad</th></tr></thead><tbody>';
		foreach ($staffPromotions as $obj) {
			if ($invoice == '')
				$invoice = $obj->getInvoiceNumber();
			$table .= '<tr class="text-center"><td><img class="image-rounded zoomA" src="../../../../'.$this->getParameter('promotion_image_path').'/'.$obj->getPromotion()->getImagePath().'"></td><td><h5>'.$obj->getQuantityTaken().'</h5></td></tr>';
		} 
		$table .= '</tbody></table>';
		/*
		$('<div class="content"/>')
            .html(json.html).appendTo('body');
		*/
		$response = array('status'=>'success',
		'table' => $table, 
		'campaign' => '<b>Campaña:</b> '.$staffCode->getCampaign()->getName(), 
		'invoice' => '<b>Factura: </b>'.$invoice, 
		'code' => '<b>Código: </b>'.$staffCode->getCode());
		return new JsonResponse($response);
	}
}