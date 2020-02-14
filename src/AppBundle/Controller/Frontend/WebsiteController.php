<?php
namespace AppBundle\Controller\Frontend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Staff;
use AppBundle\Entity\StaffCode;
use AppBundle\Form\StaffLoginType;
use AppBundle\Form\StaffType;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\RegisterPending;
use AppBundle\Helper\ApiHelper;

class WebsiteController extends Controller
{

    /**
     *
     * @Route("/", name="frontend_index")
     */
    public function landingAction (Request $request)
    {       

        return $this->render('@App/Frontend/Website/index.html.twig',  array());
    }


    /**
     *
     * @Route("/que-es-el-club", name="frontend_about")
     */
    public function aboutAction (Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Website/about.html.twig', array());
    }

    /**
     *
     * @Route("/register", name="frontend_register")
     */
    public function registerStaffAction (Request $request)
    {
        $apiHelper = new ApiHelper();	

        $form = $request->get('data');

        $register = false;
        $msg = null;
        $status = null;

        if(isset($form)){

            $register = true;

            $firstName = $form['first_name'];
			$lastName = $form['last_name'];
			$email = $form['email'];
			$citizenId = $form['citizen_id'];
			$phone = $form['phone'];
            $country = $form['country'];
            
            $postdata = json_encode(
				array(
					"first_name" => $firstName,
					"last_name" => $lastName,
					"email" => $email,
					"citizen_id" => $citizenId,
					"phone" => $phone,
					"country" => $country
				)
            );
            
            $insert = $apiHelper->connectServices("http://localhost/contenedor_4/web/app_dev.php/ws/register-staff", "POST", null, $postdata);
			$insert = json_decode($insert);

			if($insert->status == 'success'){
                $msg = $insert->msg;
                $status = "success";
			} else {
                $msg = $insert->msg;
                $status = "error";
			}
        }

        $coutries = $apiHelper->connectServices("http://localhost/contenedor_4/web/app_dev.php/ws/get-countries", "GET", null, null);
        $coutries = json_decode($coutries);
        
        if($coutries->status == 'success'){
			$list = $coutries->data;
		} else {
			$list = null;
        }

        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Website/register.html.twig', array(
            "countries" => $list,
            "register" => $register,
            "status" => $status,
            "msg" => $msg
        ));
    }

    /**
     *
     * @Route("/login", name="frontend_login")
     */
    public function loginStaffAction (Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Website/login.html.twig', array());
    }

    
}
