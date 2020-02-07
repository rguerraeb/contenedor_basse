<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\ApiHelper;

class UserController extends Controller {
	
    
    private $moduleId = 3;
    
    /**
     * @Route("/backend/user", name="backend_user")
     */
	public function indexAction(Request $request) {
		
		$this->get("session")->set("module_id", $this->moduleId);	
		$apiHelper = new ApiHelper();	
		$userData = $this->get ( "session" )->get ( "userData" );
		$userForm = $request->get('user');

		if(isset($userForm)){
			$firstName = $userForm['first_name'];
			$lastName = $userForm['last_name'];
			$email = $userForm['email'];
			$password = $userForm['password'];
			$status = $userForm['status'];
			$userRole = $userForm['user_role'];
			$distributor = $userForm['distributor'];

			$postdata = json_encode(
				array(
					"first_name" => $firstName,
					"last_name" => $lastName,
					"email" => $email,
					"password" => $password,
					"status" => $status,
					"user_role" => $userRole,
					"distributor" => $distributor
				)
			);

			$insert = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/register-user", "POST", $this->get("session")->get("tokenapp"), $postdata);
			$insert = json_decode($insert);

			if($insert->status == 'success'){
				$this->addFlash('success_message', $insert->msg);
			} else {
				$this->addFlash('error_message', $insert->msg);
			}

		}
		
		$users = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-users", "GET", $this->get("session")->get("tokenapp"), null);
		$users = json_decode($users);

		if($users->status == 'success'){
			$list = $users->data;
		} else {
			$list = null;
		}

		$roles = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-roles", "GET", $this->get("session")->get("tokenapp"), null);
		$roles = json_decode($roles);

		if($roles->status == 'success'){
			$rolesList = $roles->data;
		} else {
			$rolesList = null;
		}

		$distributors = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-distributors", "GET", $this->get("session")->get("tokenapp"), null);
		$distributors = json_decode($distributors);

		if($distributors->status == 'success'){
			$distributorsList = $distributors->data;
		} else {
			$distributorsList = null;
		}

		$mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));		
		
		return $this->render ( '@App/Backend/User/index.html.twig', array (
				"list" => $list,
				"roles" => $rolesList,
				"distributors" => $distributorsList,
				"action" => "backend_user_index",
		        "permits" => $mp
		) );
	}
	
	/**
	 * @Route("/backend/user/edit/{id}", name="backend_user_edit", requirements={"id": "\d+"})
	 */
	public function editAction(Request $request) {
		$apiHelper = new ApiHelper();
		$userData = $this->get("session")->get("userData");
		$userId = $request->get('id');
		$userForm = $request->get('user');

		$userService = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-user/$userId", "GET", $this->get("session")->get("tokenapp"), null);
		$userService = json_decode($userService);

		if ($userService->status == "success") {

			$user = $userService->data;

			$oldPassword = $user[0]->password;
			$oldEmail = $user[0]->email;

			if(isset($userForm)){
				$firstName = $userForm['first_name'];
				$lastName = $userForm['last_name'];
				$email = $userForm['email'];
				$password = $userForm['password'];
				$status = $userForm['status'];
				$userRole = $userForm['user_role'];
				$distributor = $userForm['distributor'];

				$postdata = json_encode(
					array(
						"first_name" => $firstName,
						"last_name" => $lastName,
						"email" => $email,
						"password" => $password,
						"status" => $status,
						"user_role" => $userRole,
						"distributor" => $distributor
					)
				);

				$update = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/edit-user/$userId", "PUT", $this->get("session")->get("tokenapp"), $postdata);
				$update = json_decode($update);

				if($update->status == 'success'){
					$this->addFlash('success_message', $update->msg);
					return $this->redirectToRoute ( "backend_user_edit", ["id" => $userId]);
				} else {
					$this->addFlash('error_message', $update->msg);
				}

			}

			$roles = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-roles", "GET", $this->get("session")->get("tokenapp"), null);
			$roles = json_decode($roles);
	
			if($roles->status == 'success'){
				$rolesList = $roles->data;
			} else {
				$rolesList = null;
			}
	
			$distributors = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/get-distributors", "GET", $this->get("session")->get("tokenapp"), null);
			$distributors = json_decode($distributors);
	
			if($distributors->status == 'success'){
				$distributorsList = $distributors->data;
			} else {
				$distributorsList = null;
			}

			return $this->render ( '@App/Backend/User/edit.html.twig', array(
				"roles" => $rolesList,
				"distributors" => $distributorsList,
				"user" => $user,
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
	public function deleteAction(Request $request) {

		$userId = $request->get('id');
		$userData = $this->get("session")->get("userData");
		$apiHelper = new ApiHelper();

		if ($userId) {
			// Can't delete yourself
			if ($userId != $userData->userId) {

				$delete = $apiHelper->connectServices("http://localhost/contenedor_2/web/app_dev.php/ws/delete-user/$userId", "DELETE", $this->get("session")->get("tokenapp"), null);
				var_dump($delete);
				$delete = json_decode($delete);

				if($delete->status == "success"){
					$this->addFlash ( 'success_message', $this->getParameter ( 'exito_inactivar' ) );
				}
				
			} else {
				$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
			}
		} else {
			$this->addFlash ( 'error_message', $this->getParameter ( 'error_inactivar' ) );
		}
		
		return $this->redirectToRoute ( "backend_user" );
	}

}
