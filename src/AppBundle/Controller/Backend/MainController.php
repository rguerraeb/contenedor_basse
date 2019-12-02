<?php

namespace AppBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\EbClosion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller
{
	
    
    public function mainMenuAction(Request $request)
    {
    	$userData = $this->get("session")->get("userData");
    	$userModules = $this->get("session")->get("userModules");
    	$moduleId = $this->get("session")->get("module_id");

        // Get service with function
        $service = $this->container->get('user.role.handler');
        $roleId = $service->getRoleId();

        if ($roleId == 2) {
            $roleStaff = true;
        }
        else {
            $roleStaff = false;
        }

        return $this->render('@App/Backend/main_menu.html.twig', array(
        	"userData" => $userData,
        	"userModules" => $userModules,
        	"menuId" => $this->get("session")->get("module_id"),
        	"roleStaff" => $roleStaff
        ));
    }
    
    public function profileAction(Request $request)
    {
    	$userData = $this->get("session")->get("userData");
    	$userModules = $this->get("session")->get("userModules");

    	// replace this example code with whatever you need
    	return $this->render('@App/Backend/profile.html.twig', array(
    			"userData" => $userData,
    	));
    
    }

    /**
     * @Route("/load-cities", name="backend_load_cities")
     */
    public function loadCitiesAction(Request $request) {

        $stateId = $request->get("stateId", 0);
        $type = $request->get("html", "html");

        $cities = $this->getDoctrine()->getRepository("AppBundle:City")->findBy(array("state" => $stateId), array("name" => "ASC"));

        if ($type == "html") {
            return $this->render('@App/Backend/loadCities.html.twig', array(
                "cities" => $cities,
            ));
        }

    }
   
   
}
