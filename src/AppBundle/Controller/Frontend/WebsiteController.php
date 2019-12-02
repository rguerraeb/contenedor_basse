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

class WebsiteController extends Controller
{

    /**
     *
     * @Route("/", name="frontend_index")
     */
    public function landingAction (Request $request)
    {
        $form = $this->createForm(new StaffType());
        $form->handleRequest($request);
       
        $em = $this->getDoctrine()->getManager();
       // $promotions = $em->getRepository("AppBundle:Staff")->getAllCampaingPromotions();
       
        return $this->render('@App/Frontend/Website/index.html.twig',
                array(
                        "form" => $form->createView(), 
                        //"promotions" => $promotions,
                ));
    }

    
    /**
     *
     * @Route("/register", name="frontend_register_user")
     */
    public function registerUserAction (Request $request)
    {
        $dpi = $request->get("dpi");
        $phone = $request->get("phone");
        $name = $request->get("name");
        $lastName = $request->get("lastName");
        $countryId = $request->get("country");
        $email = $request->get("email", false);

        $staff = $this->getDoctrine()
            ->getRepository('AppBundle:Staff')
            ->findOneBy(array(
                "citizenId" => $dpi,
                "phone" => $phone,
                "country" => $countryId
            ));

        if ($staff)
        {
            $response = array('status'=>'success', 'message' =>'El usuario ya se encuentra registrado');
            return new JsonResponse($response);
        } else{                       

            $em = $this->getDoctrine()->getManager();
            
            $country = $em->getRepository("AppBundle:Country")->findOneBy(array("id" => $countryId));
            
            $staff = new Staff();
            $staff->setName($name . " " . $lastName);
            $staff->setCitizenId($dpi);
            $staff->setPhone($phone);
            $staff->setCreatedAt(new \DateTime());
            $staff->setCountry($country);
            $staff->setEmail($email);

            $em->persist($staff);
            $em->flush();

            $this->get("session")->set("staff", $staff);
            
            $store = $this->getDoctrine()
            ->getRepository('AppBundle:Store')->findBy(array("country" => $country));
            $arrayStore = [];
            foreach ($store as $item) {
                array_push($arrayStore, array("store_id" => $item->getId(), "name" => $item->getStoreName()));
            }
            
            $response = array('status'=>'success', "store_list" => $arrayStore, "sid" => md5($staff->getStaffId()));        
            
        }    
        
        							
		return new JsonResponse($response);
    }

    /**
     *
     * @Route("/register_login", name="frontend_login_user")
     */
    public function loginUserAction (Request $request)
    {

        $dpi = $request->get("dpi");
        $phone = $request->get("phone");
        $country = $request->get("country");

        $staff = $this->getDoctrine()
            ->getRepository('AppBundle:Staff')
            ->findOneBy(array(
                "citizenId" => $dpi,
                "phone" => $phone,
                "country" => $country
            ));

        if ($staff) {
            
            // get stores
            $store = $this->getDoctrine()
            ->getRepository('AppBundle:Store')->findBy(array("country" => $country));
            $arrayStore = [];
            foreach ($store as $item) {
                array_push($arrayStore, array("store_id" => $item->getId(), "name" => $item->getStoreName()));                               
            }
            
            $this->get("session")->set("staff", $staff);
            $response = array('status'=>'success', "store_list" => $arrayStore, "sid" => md5($staff->getStaffId()));            
        }
        else    
            $response = array('status'=>'success', 'message' =>'El usuario no existe');
        
		return new JsonResponse($response);
    }
    
    /**
     *
     * @Route("/register_code", name="frontend_register_code")
     */
    public function registerCodeAction (Request $request)
    {
        
        $code = $request->get("code", false);
        $invoice = $request->get("invoice", false);
        $store = $request->get("store", false);
        $sid = $request->get("sid", false);              
        
        
        
        if ($code && $invoice && $store && $sid) {
            
            $staff = $this->getDoctrine()
            ->getRepository('AppBundle:Staff')
            ->findByMd5Id($sid);
            
            $response = $this->forward('AppBundle\Controller\Webservices\IndexController::registeCodeAction', [
                  "staff_id" =>$staff["staff_id"],
                   "code" => $code,
                   "store_id" => $store,
                   "bill_number" => $invoice
            ]);
            
            $result = json_decode($response->getContent(), true);
            
            if ($result["status"] == "ok") {
                $response = array('error'=>'success');
            } else {
                $response = array('error'=>'success', "message" => $result["code"]);
            }
            
            
            
        }
        else
            $response = array('status'=>'success', 'message' =>'Ocurrio un error al validar los datos, por favor intentelo de nuevo.');
            
        return new JsonResponse($response);
    }

    /**
     *
     * @Route("/", name="frontend_index_initial")
     */
    public function indexAction (Request $request)
    {

        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Website/index.html.twig',
                array(
                        //"form" => $form->createView(),
                        //"siteContent" => $siteContent
                ));
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

    
}
