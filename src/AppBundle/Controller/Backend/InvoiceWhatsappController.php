<?php
namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\ApiHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvoiceWhatsappController extends Controller
{

    private $moduleId = 18;

    /**
     *
     * @Route("/backend/invoices-pending", name="backend_invoice_whatsapp")
     */
    public function indexAction (Request $request)
    {
        $validateSession = EbClosion::validateSession();
		if($validateSession == false){
			$this->addFlash('login_error', "Session no iniciada.");
			return $this->redirectToRoute("backend_login_user");
        }
        
        $this->get("session")->set("module_id", $this->moduleId);
        $apiHelper = new ApiHelper();	
        $userData = $this->get ( "session" )->get ( "userData" );

        $invoices = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-invoices", "GET", $this->get("session")->get("tokenapp"), null);
        $invoices = json_decode($invoices);
        
        if($invoices->status == 'success'){
			$list = $invoices->data;
		} else {
			$list = null;
        }

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
        
        return $this->render('@App/Backend/InvoiceWhatsapp/index.html.twig',
            array(
                "list" => $list,
                "permits" => $mp
            ));
    }

    /**
     *
     * @Route("/backend/invoices-pending/edit/{id}", name="backend_invoice_edit")
     */
    public function editAction (Request $request)
    {
        $validateSession = EbClosion::validateSession();
		if($validateSession == false){
			$this->addFlash('login_error', "Session no iniciada.");
			return $this->redirectToRoute("backend_login_user");
        }
        
        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
        $invoiceId = $request->get('id');
        
        if(isset($invoiceId)){

            $products = $request->get("prod");

            if(isset($products)){

                $postdata = json_encode(
                    array(
                        "invoice_pending_status" => 'ACEPTADO',
                        "products" => $products
                    )
                );

                $process = $apiHelper->connectServices($this->getParameter("contenedor_3")."register-points-assign/$invoiceId", "POST", $this->get("session")->get("tokenapp"), $postdata);
                var_dump($process);
                $process = json_decode($process);
                
                var_dump($process);

                if($process->status == 'success'){
                    $this->addFlash('success_message', $process->msg);
                } else {
                    $this->addFlash('error_message', $process->msg);
                }

                return $this->redirectToRoute("backend_invoice_whatsapp");

            }

            $invoice = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-invoice/$invoiceId", "GET", $this->get("session")->get("tokenapp"), null);
            $invoice = json_decode($invoice);
            
            if($invoice->status == 'success'){
                $list = $invoice->data;
            } else {
                $list = null;
            }

            $skus = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-skus", "GET", $this->get("session")->get("tokenapp"), null);
            $skus = json_decode($skus);
            
            if($skus->status == 'success'){
                $skuList = $skus->data;
            } else {
                $skuList = null;
            }

        } else {
            $this->addFlash('error_message', $this->getParameter('error_editar'));
            return $this->redirectToRoute("backend_invoice_whatsapp");
        }
        
        return $this->render('@App/Backend/InvoiceWhatsapp/edit.html.twig',
            array(
                "invoice" => $list,
                "skuList" => $skuList
            ));

    }

    /**
     *
     * @Route("/backend/invoices-pending/load-product-type", name="backend_invoice_load_type")
     */
    public function loadProductTypeAction (Request $request)
    {
        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
        $skuId = $request->get("sid");
        if ($skuId) {

            $service = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-sku/$skuId", "GET", $this->get("session")->get("tokenapp"), null);
            $service = json_decode($service);
            
            if($service->status == 'success'){
                $sku = $service->data;
            } else {
                $sku = null;
            }

            return new JsonResponse(array(
                "brand" => $sku[0]->model
            ));
        } else {
            return new JsonResponse(array(
                false
            ));
        }
    }

    /**
     *
     * @Route("/backend/invoices-pending/process/{id}/{status}", name="backend_invoice_process")
     */
    public function processAction (Request $request)
    {
        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
        $invoiceId = $request->get('id');
        $status = $request->get('status');

        if(isset($invoiceId)){

            $postdata = json_encode(
                array(
                    "invoice_pending_status" => $status
                )
            );

            $process = $apiHelper->connectServices($this->getParameter("contenedor_3")."register-points-assign/$invoiceId", "POST", $this->get("session")->get("tokenapp"), $postdata);
            var_dump($process);
            $process = json_decode($process);
            
            var_dump($process);

			if($process->status == 'success'){
				$this->addFlash('success_message', $process->msg);
			} else {
				$this->addFlash('error_message', $process->msg);
			}

        } else {
            $this->addFlash('error_message', $this->getParameter('error_editar'));
        }

        return $this->redirectToRoute("backend_invoice_whatsapp");

    }

}
