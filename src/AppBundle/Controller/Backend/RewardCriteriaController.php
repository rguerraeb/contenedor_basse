<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\ApiHelper;

class RewardCriteriaController extends Controller
{
    
    private $moduleId = 9;
    
    /**
     * @Route("/backend/reward-criteria", name="backend_reward_criteria")
     */
    public function indexAction(Request $request)
    {
        $validateSession = EbClosion::validateSession();
		if($validateSession == false){
			$this->addFlash('login_error', "Session no iniciada.");
			return $this->redirectToRoute("backend_login_user");
		}

        $this->get("session")->set("module_id", $this->moduleId);
        $apiHelper = new ApiHelper();	
        $userData = $this->get ( "session" )->get ( "userData" );
        $rewardCriteriaForm = $request->get('reward_criteria');
    	
        if(isset($rewardCriteriaForm)){
            $mathematicalOperator = $rewardCriteriaForm['mathematicalOperator'];
			$distributorId = $rewardCriteriaForm['distributor'];
			$filterGroupId = $rewardCriteriaForm['filterGroup'];

			$postdata = json_encode(
				array(
					"mathematical_operator" => $mathematicalOperator,
					"distributor_id" => $distributorId,
					"filter_group_id" => $filterGroupId
				)
            );
            
            $insert = $apiHelper->connectServices($this->getParameter("contenedor_2")."register-reward-criteria", "POST", $this->get("session")->get("tokenapp"), $postdata);
			$insert = json_decode($insert);

			if($insert->status == 'success'){
				$this->addFlash('success_message', $insert->msg);
			} else {
				$this->addFlash('error_message', $insert->msg);
			}
        }

        $rewardCriterias = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-rewards-criteria", "GET", $this->get("session")->get("tokenapp"), null);
		$rewardCriterias = json_decode($rewardCriterias);

		if($rewardCriterias->status == 'success'){
			$rewardCriteriasList = $rewardCriterias->data;
		} else {
			$rewardCriteriasList = null;
        }
        
        $distributors = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-distributors", "GET", $this->get("session")->get("tokenapp"), null);
        $distributors = json_decode($distributors);

		if($distributors->status == 'success'){
			$distributorsList = $distributors->data;
		} else {
			$distributorsList = null;
        }
        
        $filtersGroup = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-filters-group", "GET", $this->get("session")->get("tokenapp"), null);
        $filtersGroup = json_decode($filtersGroup);

		if($filtersGroup->status == 'success'){
			$filtersGroupList = $filtersGroup->data;
		} else {
			$filtersGroupList = null;
        }
        
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/RewardCriteria/index.html.twig', array(
                "list" => $rewardCriteriasList,
                "distributors" => $distributorsList,
                "filtersGroup" => $filtersGroupList,
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/reward-criteria/edit/{id}", name="backend_reward_criteria_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request) 
    {
        $validateSession = EbClosion::validateSession();
		if($validateSession == false){
			$this->addFlash('login_error', "Session no iniciada.");
			return $this->redirectToRoute("backend_login_user");
		}

        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
		$rewardCriteriaId = $request->get('id');
        $rewardCriteriaForm = $request->get('reward_criteria');
        
        $rewardCriteriaService = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-reward-criteria/$rewardCriteriaId", "GET", $this->get("session")->get("tokenapp"), null);
        $rewardCriteriaService = json_decode($rewardCriteriaService);

        if ($rewardCriteriaService->status == "success") {

            $rewardCriteria = $rewardCriteriaService->data;

            if(isset($rewardCriteriaForm)){
                $mathematicalOperator = $rewardCriteriaForm['mathematicalOperator'];
                $distributorId = $rewardCriteriaForm['distributor'];
                $filterGroupId = $rewardCriteriaForm['filterGroup'];

                $postdata = json_encode(
                    array(
                        "mathematical_operator" => $mathematicalOperator,
                        "distributor_id" => $distributorId,
                        "filter_group_id" => $filterGroupId
                    )
                );

                $update = $apiHelper->connectServices($this->getParameter("contenedor_2")."edit-reward-criteria/$rewardCriteriaId", "PUT", $this->get("session")->get("tokenapp"), $postdata);
				$update = json_decode($update);

				if($update->status == 'success'){
					$this->addFlash('success_message', $update->msg);
				} else {
                    $this->addFlash('error_message', $update->msg);
                }
                
                return $this->redirectToRoute ( "backend_reward_criteria_edit", ["id" => $rewardCriteriaId]);
            }

            $distributors = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-distributors", "GET", $this->get("session")->get("tokenapp"), null);
            $distributors = json_decode($distributors);

            if($distributors->status == 'success'){
                $distributorsList = $distributors->data;
            } else {
                $distributorsList = null;
            }
            
            $filtersGroup = $apiHelper->connectServices($this->getParameter("contenedor_2")."get-filters-group", "GET", $this->get("session")->get("tokenapp"), null);
            $filtersGroup = json_decode($filtersGroup);

            if($filtersGroup->status == 'success'){
                $filtersGroupList = $filtersGroup->data;
            } else {
                $filtersGroupList = null;
            }

            return $this->render ( '@App/Backend/RewardCriteria/edit.html.twig', array(
                "rewardCriteria" => $rewardCriteria,
                "distributors" => $distributorsList,
                "filtersGroup" => $filtersGroupList
            ) );

        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }

        return $this->redirectToRoute ( "backend_reward_criteria" );

    }

    /**
     * @Route("/backend/reward-criteria/delete/{id}", name="backend_reward_criteria_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request) 
    {
        $validateSession = EbClosion::validateSession();
		if($validateSession == false){
			$this->addFlash('login_error', "Session no iniciada.");
			return $this->redirectToRoute("backend_login_user");
		}

        $apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
        $rewardCriteriaId = $request->get('id');

        if ($rewardCriteriaId) {

			$delete = $apiHelper->connectServices($this->getParameter("contenedor_2")."delete-sku/$rewardCriteriaId", "DELETE", $this->get("session")->get("tokenapp"), null);
			$delete = json_decode($delete);

			if($delete->status == "success"){
				$this->addFlash('success_message', $this->getParameter('exito_eliminar'));
			} else {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
				
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
		}
		
		return $this->redirectToRoute ( "backend_reward_criteria" );

    }

}