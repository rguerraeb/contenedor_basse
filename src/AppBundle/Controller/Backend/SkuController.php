<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\ApiHelper;

class SkuController extends Controller
{
    
    private $moduleId = 8;
    
    /**
     * @Route("/backend/sku", name="backend_sku")
     */
    public function indexAction(Request $request)
    {
        
        $this->get("session")->set("module_id", $this->moduleId);
        $apiHelper = new ApiHelper();	
        $userData = $this->get ( "session" )->get ( "userData" );
        $skuForm = $request->get('sku');
    	
        if(isset($skuForm)){
            $skuFilterString = $skuForm['sku_filter_string'];
			$skuCategory = $skuForm['sku_category'];
			$code = $skuForm['code'];
			$type = $skuForm['type'];
			$status = $skuForm['status'];

			$postdata = json_encode(
				array(
					"sku_filter_string" => $skuFilterString,
					"sku_category" => $skuCategory,
					"code" => $code,
					"type" => $type,
					"status" => $status
				)
            );
            
            $insert = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/register-sku", "POST", $this->get("session")->get("tokenapp"), $postdata);
			$insert = json_decode($insert);

			if($insert->status == 'success'){
				$this->addFlash('success_message', $insert->msg);
			} else {
				$this->addFlash('error_message', $insert->msg);
			}

        }

        $skus = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-skus", "GET", $this->get("session")->get("tokenapp"), null);
        $skus = json_decode($skus);
        
        if($skus->status == 'success'){
			$list = $skus->data;
		} else {
			$list = null;
        }
        
        $skuCategories = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-skus-categories", "GET", $this->get("session")->get("tokenapp"), null);
		$skuCategories = json_decode($skuCategories);

		if($skuCategories->status == 'success'){
			$skuCategoriesList = $skuCategories->data;
		} else {
			$skuCategoriesList = null;
		}
        
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/Sku/index.html.twig', array(
                "list" => $list,
                "skuCategories" => $skuCategoriesList,
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/sku/edit/{id}", name="backend_sku_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request) {

        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
		$skuId = $request->get('id');
		$skuForm = $request->get('sku');

        $skuService = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-sku/$skuId", "GET", $this->get("session")->get("tokenapp"), null);
        $skuService = json_decode($skuService);
        
        if ($skuService->status == "success") {

            $sku = $skuService->data;

            if(isset($skuForm)){
                $skuFilterString = $skuForm['sku_filter_string'];
                $skuCategory = $skuForm['sku_category'];
                $code = $skuForm['code'];
                $type = $skuForm['type'];
                $status = $skuForm['status'];

                $postdata = json_encode(
                    array(
                        "sku_filter_string" => $skuFilterString,
                        "sku_category" => $skuCategory,
                        "code" => $code,
                        "type" => $type,
                        "status" => $status
                    )
                );

                $update = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/edit-sku/$skuId", "PUT", $this->get("session")->get("tokenapp"), $postdata);
				$update = json_decode($update);

				if($update->status == 'success'){
					$this->addFlash('success_message', $update->msg);
					return $this->redirectToRoute ( "backend_sku_edit", ["id" => $skuId]);
				} else {
					$this->addFlash('error_message', $update->msg);
				}
            }

            $skuCategories = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-skus-categories", "GET", $this->get("session")->get("tokenapp"), null);
            $skuCategories = json_decode($skuCategories);

            if($skuCategories->status == 'success'){
                $skuCategoriesList = $skuCategories->data;
            } else {
                $skuCategoriesList = null;
            }

            return $this->render ( '@App/Backend/Sku/edit.html.twig', array(
                "sku" => $sku,
                "skuCategories" => $skuCategoriesList
            ) );

        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }

        return $this->redirectToRoute ( "backend_sku" );
    }

    /**
     * @Route("/backend/sku/delete/{id}", name="backend_sku_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request) {

		$apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
        $skuId = $request->get('id');

        if ($skuId) {

			$delete = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/delete-sku/$skuId", "DELETE", $this->get("session")->get("tokenapp"), null);
			$delete = json_decode($delete);

			if($delete->status == "success"){
				$this->addFlash('success_message', $this->getParameter('exito_inactivar'));
			}
				
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_sku" );
        
    }

}