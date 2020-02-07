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
    	$userModules = $this->get("session")->get("userModules");
    	$moduleId = $this->get("session")->get("module_id");

        return $this->render('@App/Backend/main_menu.html.twig', array(
        	"userModules" => $userModules,
        	"menuId" => $moduleId
        ));
    }
    
    public function profileAction(Request $request)
    {
    	$userData = $this->get("session")->get("userData");

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
