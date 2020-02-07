<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\UserLoginType;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\ApiHelper;
use AppBundle\Helper\WebServiceHelper;

class IndexController extends Controller
{
	
	/**
	 * @Route("/backend/checkmp", name="backend_check_mp")
	 */
	public function checkmpAction(Request $request) {
		$moduleId =  $this->get ( "session" )->get ( "module_id" );
		$userModules =  $this->get ( "session" )->get ( "userModules" );
		

		$checkModuleView = false;
		foreach ($userModules as $module) {
			if ($module["mcid"] == $moduleId && $module["viewm"] == 1) {
				$checkModuleView = true;
			}
		}
		if (!$checkModuleView) 
			$this->addFlash ( 'login_error', $this->getParameter ( 'invalid_module' ) );
		
		
		return new JsonResponse(array('view' => $checkModuleView));
	}
	
	
	
	
	/**
	 * @Route("/backend/", name="backend_index")
	 */
	public function getindexAction(Request $request)
	{				
		// replace this example code with whatever you need
		return $this->redirectToRoute("backend_login_user");
	
	}
	
    /**
     * @Route("/backend/login", name="backend_login")
     */
    public function indexAction(Request $request)
    {
    	$user = new User();
    	$form = $this->createForm ( new UserLoginType(), $user );

    	
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // replace this example code with whatever you need
        return $this->render('@App/Backend/login_old.html.twig', array(
        	"form" => $form->createView (),
            'error' => $error
        ));
    }
	
	/**
	 * @Route("/backend/login-user", name="backend_login_user") 
	 */	
	public function loginAction(Request $request)
	{
		$response = [];

		$apiHelper = new ApiHelper();

		$url = "http://localhost/contenedor_2/web/app_dev.php/ws/login-user";
		$method = "POST";
		$tokenApp = null;

		$email = $request->get("_username");
		$password = $request->get("_password");

		if(isset($email) && isset($password)){
			$postdata = json_encode(
				array(
					"email" => $email,
					"password" => $password
				)
			);

			$response = $apiHelper->connectServices($url, $method, $tokenApp, $postdata);
			$response = json_decode($response);

			if($response->status == 'success'){
				$tokenApp = $response->data;
				
				//session
				$session = $request->getSession();
				$session->set("tokenapp", $tokenApp);

				$userToken = WebServiceHelper::decodeJWTToken($tokenApp);

				$modules = $userToken->modules;
				$session->set("userModules",$modules);
				$session->set("userData", $userToken);

				if($userToken->mainModule){

					$session->set("module_id", $userToken->mainModule->mcid);
					return $this->redirectToRoute($userToken->mainModule->url_access);
					
				}
			}

		} 

        return $this->render('@App/Backend/login.html.twig', array(
        	"response" => $response
        ));
	}

    /**
     * @Route("/backend/main", name="backend_main")
     */
    public function mainAction(Request $request)
    {
    	
    	$this->get("session")->set("module_id", 1);
		$counter = array();
    	// replace this example code with whatever you need
    	return $this->render('@App/Backend/dashboard.html.twig', array(
    		'counter'=>$counter 
    	));
    
    }
    
    /**
     * @Route("/backend/logout", name="backend_logout")
     */
    public function logoutAction(Request $request)
    {
    	$this->get('security.context')->setToken(null);
    	$session = $this->get('request')->getSession();
    	$session->clear();
    	$session->invalidate(1);
    	return $this->redirectToRoute("backend_login_user");
    
    }
}
