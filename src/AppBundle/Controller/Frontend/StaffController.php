<?php
namespace AppBundle\Controller\Frontend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Helper\SessionHelper;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\EbClosion;
use AppBundle\Entity\Staff;
use AppBundle\Entity\StaffLog;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Form\MyProfileType;
use AppBundle\Helper\ApiHelper;

class StaffController extends Controller
{

    private $moduleId = 13;

    /**
     *
     * @Route("/staff/my-profile", name="staff_profile")
     */
    public function myProfileAction (Request $request)
    {
        
        $staff = $this->getUser();
        
        $em = $this->getDoctrine()->getManager();

        $userData = $this->get ( "session" )->get ( "userData" );

        $createdBy = $userData['staff_id'];

        // Check that it's not a deleted staff
        $staffStatus = $staff->getStaffStatus();
        if ($staffStatus && $staffStatus->getId() == 3) {
            // Deleted staff, can't edit
            throw $this->createNotFoundException('No existe');
        }

        // Add points of sale
        $staffPointsOfSale = $em->getRepository('AppBundle:StaffPointOfSale')->findBy(
                array(
                        'staff' => $staff,
                        'status' => 'ACTIVE'
                ));

        // Points of sale saved in database
        $originalPos = new ArrayCollection();
        $cloneSpos = array();
        foreach ($staffPointsOfSale as $staffPointOfSale) {
            $originalPos->add($staffPointOfSale);

            array_push($cloneSpos, clone $staffPointOfSale);
        }

        // Set it for it to be on the form
        $staff->setStaffPointsOfSale($originalPos);
        
        $form = $this->createForm(new MyProfileType(), $staff, array("showPointOfSale" => true));

        // Old info
        $oldFirstName = $staff->getFirstName();
        $oldLastName = $staff->getLastName();
        $oldBirthdate = $staff->getBirthdate();
        $oldCitizenId = $staff->getCitizenId();
        $oldEmail = $staff->getEmail();
        $oldPhoneSecondary = $staff->getPhoneSecondary();
        $oldPhoneMain = $staff->getPhoneMain();
        $oldExperienceYears = $staff->getExperienceYears();
        $oldProfileImage = $staff->getProfileImage();
        $oldTaxIdentifier = $staff->getTaxIdentifier();
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) { 
                    
                    
                $saveError = false;
                $profilePicture = $request->files->get("profileImage");
                if ($profilePicture) {                    
                    $extension = $profilePicture->guessExtension();
                    
                    if (!($extension == 'jpg' || $extension == 'jpeg' ||
                            $extension == 'png' || $extension == 'JPG' ||
                            $extension == 'JPEG' || $extension == 'PNG')) {
                                
                                $this->addFlash('error_message',
                                        $this->getParameter('extension_error_invoice'));
                                $saveError = true;
                            } else {
                                $fileName = md5(uniqid()) . '.' . $profilePicture->guessExtension();
                                $profilePicture->move($this->getParameter('profile_image'),
                                        $fileName);
                                $staff->setProfileImage($fileName);
                            }
                }

                    $firstName = $form->get('firstName')->getData();            
                    $lastName = $form->get("lastName")->getData();             
                    $birthdate = $form->get("birthdate")->getData();             
                    $citizenId = $request->get("citizenId");
                    $email = $form->get("email")->getData();             
                    $phoneSecondary = $form->get("phoneSecondary")->getData();             
                    $phoneMain = $request->get("phoneMain");             
                    $experienceYears = $form->get("experienceYears")->getData();             
                    $profileImage = $fileName;  
                    
                    $arrayLog = array("oldFirstName"=>$oldFirstName, "oldLastName"=>$oldLastName, "oldBirthdate"=>$oldBirthdate, "oldCitizenId"=>$oldCitizenId, "oldEmail"=>$oldEmail, "oldPhoneSecondary"=>$oldPhoneSecondary, "oldPhoneMain"=>$oldPhoneMain, "oldExperienceYears"=>$oldExperienceYears, "oldProfileImage"=>$oldProfileImage, "firstName"=>$firstName, "lastName"=>$lastName, "birthdate"=>$birthdate, "citizenId"=>$citizenId, "email"=>$email, "phoneSecondary"=>$phoneSecondary, "phoneMain"=>$phoneMain, "experienceYears"=>$experienceYears, "profileImage"=>$profileImage);
                    $jsonLog = json_encode($arrayLog);
        var_dump($jsonLog);
                    
                    //die();
                
                if (!$saveError) {
                    $userHelper = $this->get('user.helper');
                    $staff->setPasswd($userHelper->encodePassword($staff, $citizenId));
                    $staff->setCitizenId($citizenId);
                    $staff->setPhoneMain($phoneMain);
                    $em->persist($staff);
                    $em->flush();

                        $staffLog = new StaffLog();
                        $staffLog->setStaff($staff);
                        $staffLog->setLog($jsonLog);
                        $staffLog->setCreatedAt(new \DateTime());
                        $staffLog->setCreatedBy($createdBy);
                        $em->persist( $staffLog );
			$em->flush();

                    $this->addFlash('success_message',
                            $this->getParameter('exito_actualizar'));
                    return $this->redirectToRoute("staff_profile");
                }
                
            } else {
                // Error validation
                $this->addFlash('error_message',
                        $this->getParameter('error_form'));
            }
        }
        return $this->render('@App/Frontend/Staff/my-profile.html.twig',
                array(
                        "form" => $form->createView(),
                        "staffProfile" => $staff
                ));
    }

    /**
     *
     * @Route("/staff/checkmp", name="staff_check_mp")
     */
    public function checkmpAction (Request $request)
    {
        $moduleId = $this->get("session")->get("module_id");
        $userModules = $this->get("session")->get("userModules");

        $checkModuleView = false;
        foreach ($userModules as $module) {
            if ($module["mcid"] == $moduleId && $module["viewm"] == 1) {
                $checkModuleView = true;
            }
        }
        if (! $checkModuleView)
            $this->addFlash('login_error',
                    $this->getParameter('invalid_module'));

        return new JsonResponse(array(
                'view' => $checkModuleView
        ));
    }

    /**
     *
     * @Route("/staff/prize", name="staff_prize")
     */
    public function prizeAction (Request $request)
    {

        // $this->get("session")->set("module_id", 13);
        $counter = array();
        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Console/Prize/prizeList.html.twig',
                array(
                        'counter' => $counter
                ));
    }

    /**
     *
     * @Route("/staff/sales", name="staff_sales")
     */
    public function salesAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $this->get("session")->set("module_id", 13);
        $userData = $this->get("session")->get("userData");
        $saleStats = $em->getRepository("AppBundle:Staff")->getPointsForPlot(
                array(
                        'parent' => NULL,
                        'children' => array(
                                $userData["staff_id"]
                        )
                ), 1, 12);
        $skuSaleSummary = $em->getRepository("AppBundle:Staff")->getSkuSaleSummary(
                array(
                        'parent' => NULL,
                        'children' => array(
                                $userData["staff_id"]
                        )
                ), 1, 12);
        $pointDetail = $em->getRepository("AppBundle:Staff")->getPromotionalPoints(
                array(
                        $userData["staff_id"]
                ), 1, 12);

        // Get first first children in tree
        $tree = array();
        $jobPosition = $this->getUser()->getJobPosition();

        if ($jobPosition) {
            $jobPositionId = $jobPosition->getId();

            if ($jobPositionId != 5 && $jobPositionId != 6) {
                // Get tree
                $tree = $em->getRepository('AppBundle:Staff')->findLowerJobPosition(
                        $this->getUser(), array(
                                $jobPositionId
                        ));
            }
        }

        // Variables default value
        $goal = NULL;
        $progress = NULL;
        $staff = $this->getUser();

        // Check if there is an active goal
        $now = new \DateTime();
        $goalEs = $em->getRepository('AppBundle:Goal')->intersects($now, $now,
                array(
                        $staff->getJobPosition()
                ), NULL);
        if (sizeof($goalEs) > 0) {
            $goalE = $goalEs[0];
        } else {
            $goalE = NULL;
        }

        if ($goalE) {
            // Get start of goal
            $goalStart = $goalE->getStart();

            // Get end of goal
            $goalEnd = $goalE->getEnd();

            if ($staff->isSeller()) {
                // Calculate progress
                $progress = $em->getRepository('AppBundle:Sale')->count($staff,
                        $goalStart->format('Y-m-d 00:00:00'),
                        $goalEnd->format('Y-m-d 23:59:59'));
            } else {
                // Calculate progress
                $progress = $em->getRepository('AppBundle:Sale')->countHierarchy(
                        $staff, $goalStart->format('Y-m-d 00:00:00'),
                        $goalEnd->format('Y-m-d 23:59:59'));
            }
        }

        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Staff/sales.html.twig',
                array(
                        "data" => $saleStats,
                        "skuSaleSummary" => $skuSaleSummary,
                        "pointDetail" => $pointDetail,
                        'tree' => $tree,
                        'staff' => $staff,
                        'progress' => $progress,
                        'goal' => $goalE
                ));
    }

    /**
     *
     * @Route("/staff/sku-sale-sumary", name="staff_sku_sale_sumary")
     */
    public function skuSaleSummaryAction (Request $request)
    {
        // Get post parameters
        $params = array();
        $content = $this->get("request")->getContent();
        if (! empty($content)) {
            $params = json_decode($content, true);
        }

        $minMonth = $params['minMonth'];
        $maxMonth = $params['maxMonth'];
        $rootId = $params['root'];

        $em = $this->getDoctrine()->getManager();
        $staffSales = array();

        // Get sales of every selected staff
        foreach ($params['staffInfo'] as $staffInfo) {
            $thisStaffSales = $em->getRepository("AppBundle:Staff")->getPointsForPlot(
                    $staffInfo, $minMonth, $maxMonth);

            // Merge sales with other sales
            $staffSales = $this->addSalesForPlot($staffSales, $thisStaffSales);
        }

        // Return JSON with new info
        return new JsonResponse($staffSales);
    }

    /**
     *
     * @Route("/staff/sku-sale-sumary-cc", name="staff_sku_sale_sumary_cc")
     */
    public function skuSaleSummaryCcAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Get post parameters
        $params = array();
        $content = $this->get("request")->getContent();
        if (! empty($content)) {
            $params = json_decode($content, true);
        }

        $minMonth = $params['minMonth'];
        $maxMonth = $params['maxMonth'];
        $rootId = $params['root'];

        $skuSales = array();

        // Get sales of every selected staff
        foreach ($params['staffInfo'] as $staffInfo) {
            $thisSkuSales = $em->getRepository("AppBundle:Staff")->getSkuSaleSummary(
                    $staffInfo, $minMonth, $maxMonth);

            // Merge sales with other sales
            $skuSales = $this->addSkuSales($skuSales, $thisSkuSales);
        }

        if (! isset($staffInfo['children'])) {
            $staffInfo['children'] = array();
        }
        $pointDetail = $em->getRepository("AppBundle:Staff")->getPromotionalPoints(
                $staffInfo['children'], $minMonth, $maxMonth);

        // Return JSON with new info
        return new JsonResponse(
                array(
                        'skuSales' => $skuSales,
                        'pointDetail' => $pointDetail
                ));
    }

    /**
     *
     * @Route("/staff/register_sale", name="staff_register_sale")
     */
    public function registerSaleAction (Request $request)
    {
        $this->get("session")->set("module_id", 20);
        $userData = $this->get("session")->get('userData');
        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Staff/register_sale.html.twig',
                array(
                        'userData' => $userData['phone_main'],
                        'url_entrance' => $this->getParameter('url_entrance')
                ));
    }

    /**
     *
     * @Route("/staff/award", name="staff_award")
     */
    public function awardAction (Request $request)
    {
        $this->get("session")->set("module_id", 14);
        $counter = array();
        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Staff/sales.html.twig',
                array(
                        'counter' => $counter
                ));
    }

    /**
     *
     * @Route("/staff/points", name="staff_points")
     */
    public function pointsAction (Request $request)
    {
        $this->get("session")->set("module_id", 15);

        $userData = $this->get("session")->get("userData");
        $pointList = $this->getDoctrine()
            ->getRepository('AppBundle:SaleStaff')
            ->findBy(
                array(
                        "staff" => $userData["staff_id"],
                        'isCancelled' => 0
                ), array(
                        'createdAt' => 'DESC'
                ));

        return $this->render('@App/Frontend/Staff/points.html.twig',
                array(
                        "pointList" => $pointList
                ));
    }

    /**
     *
     * @Route("/staff/exchange", name="staff_exchange")
     */
    public function exchangeAction (Request $request)
    {
        $this->get("session")->set("module_id", 22);

        $userData = $this->get("session")->get("userData");
        $staffId = $userData->userId;
        $token = $this->get("session")->get("tokenapp");
        
        $apiHelper = new ApiHelper();
        
        $service = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-prize-staff/$staffId", "GET", $token, null);
        
        $service = json_decode($service);
                
        if($service->status == 'success'){
                $exchangeList = $service->data;
        } else {
                $exchangeList = null;
        }

        $mp = EbClosion::getModulePermission(22, $this->get("session")->get("userModules"));  

        return $this->render('@App/Frontend/Staff/exchange.html.twig',
                array(
                        'list' => $exchangeList,
                        "permits" => $mp
                ));
    }

    /**
     *
     * @Route("/staff/main", name="staff_main")
     */
    public function mainAction (Request $request)
    {
        $this->get("session")->set("module_id", 20);

        $userData = $this->get("session")->get("userData");

        $countryId = $userData->countryId;
        $token = $this->get("session")->get("tokenapp");
                
        $apiHelper = new ApiHelper();
                
        $service = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-news/$countryId", "GET", $token, null);
                
        $service = json_decode($service);
                        
        if($service->status == 'success'){
                $news = $service->data;
        } else {
                $news = null;
        }
        
        $mp = EbClosion::getModulePermission(20, $this->get("session")->get("userModules"));  
        
        return $this->render('@App/Frontend/Staff/dashboard.html.twig',
                array(
                        'today' => new \DateTime(),
                        'news' => $news,
                        "permits" => $mp
                ));
    }

    /**
     *
     * @Route("/staff/logout", name="staff_logout")
     */
    public function logoutAction (Request $request)
    {
        $this->get('security.context')->setToken(null);
        // $session = $this->get('request')->getSession();
        // $session->clear();
        // $session->invalidate(1);
        return $this->redirectToRoute("backend_login");
    }

    /**
     *
     * @Route("/staff/redeem", name="backend_staff_redeem")
     */
    public function redeemAction (Request $request)
    {

        $this->get("session")->set("module_id", 21);

        $userData = $this->get("session")->get("userData");
        $token = $this->get("session")->get("tokenapp");

        $apiHelper = new ApiHelper();

        $service = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-prizes", "GET", $token, null);

        $service = json_decode($service);
        
        if($service->status == 'success'){
		$prizes = $service->data;
	} else {
		$prizes = null;
        }

        $mp = EbClosion::getModulePermission(21, $this->get("session")->get("userModules"));    

        return $this->render('@App/Frontend/Staff/prize.html.twig',
                array(
                        "list" => $prizes,
                        "permits" => $mp
                ));
    }

    /**
     *
     * @Route("/staff/prizeDetails/{id}", name="staff_prize_details", requirements={"id": "\d+"})
     */
    public function prizeDetailsAction (Request $request)
    {

        $this->get("session")->set("module_id", 21);

        $prizeId = $request->get("id");
        $userData = $this->get("session")->get("userData");
        $token = $this->get("session")->get("tokenapp");

        $apiHelper = new ApiHelper();
        
        $service = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-prize/$prizeId", "GET", $token, null);
        
        $service = json_decode($service);
                
        if($service->status == 'success'){
                $prize = $service->data;
                
                if(isset($prize)){
                        $prizeIdService = $prize[0]->prizeId;

                        $serviceStep = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-prize-step/$prizeIdService", "GET", $token, null);

                        $serviceStep = json_decode($serviceStep);

                        if($serviceStep->status == 'success'){
                                $step = $serviceStep->data;
                        } else {
                                $step = null;
                        }
                }
        } else {
                $prize = null;
                $step = null;
        }

        return $this->render('@App/Frontend/Staff/prizeDetail.html.twig',
                array(
                        "prize" => $prize,
                        "prizeSteps" => $step,
                        "staff" => $userData
                ));
    }

    /**
     *
     * @Route("/staff/getPrize/{id}/{channel}", name="staff_get_prize")
     */
    public function getPrizeAction (Request $request)
    {
        /*$channel_exchange = "WEB";
        $prize_id = $request->get("id", "");
        $connection = $request->get("conn", "");
        $channel_exchange = $request->get("channel", "");
        $user_get = $request->get("user", "");
        if ($user_get <= 0) {
            $user = $this->get("session")->get("userData");
            $user_id = $user["staff_id"];
        } else {
            $user_id = $user_get;
        }

        $em = $this->getDoctrine()->getManager();
        $credit_arr = $em->getRepository('AppBundle:SaleStaff')->getStaffAvailablePoints(
                $user_id);
        $user_info = $em->getRepository('AppBundle:Staff')->findOneByStaffId(
                $user_id);
        $area_code = "502";
        // puntos Disponibles a la fecha
        $available_credits = $credit_arr[0]["points"];

        // obtener informacion del premio a canjear
        $prize_info = $em->getRepository('AppBundle:Prize')->findOneById(
                $prize_id);
        $prize_credits = $prize_info->getAmount();
        $prize_amount = $prize_info->getValue();
        $prize_quantity = $prize_info->getQuantity();
        $prize_exchange_arr = $em->getRepository('AppBundle:PrizeExchange')->getAllPrizeExchange(
                $prize_id);
        $prize_exchange = $prize_exchange_arr[0]["exchange"];
        $flag_exchange = true;

        $code = "";
        $sms_response = $prize_info->getSmsResponse();
        $phone = $user_info->getPhoneMain();
        $user_name = $user_info->getName();
        $message = "";

        $mostrar_mensaje_original = false;

        if ($prize_credits > $available_credits) {
            $message = $this->container->getParameter('error_puntos_sms');
            $sms_response = $this->container->getParameter('error_puntos_sms');
            $flag_exchange = false;
            $mostrar_mensaje_original = true;
        }

        if ($prize_exchange >= $prize_quantity && $prize_quantity != "") {
            $message = $this->container->getParameter('error_canje');
            $sms_response = $this->container->getParameter('error_canje_sms');
            $flag_exchange = false;
            $mostrar_mensaje_original = true;
        }

        $pointsToExchange = $prize_credits;
        $new_credit = $available_credits - $prize_credits;
        $sessionHelper = new SessionHelper($request->getSession(),
                $this->getDoctrine());
        $detail = $em->getRepository('AppBundle:PrizeExchange')->getReddemPointsNeeded(
                $user_info->getStaffId(), $prize_credits);
        // /////////FALSE DEL CODIGO IMPIDE CANJEE///////////////////////
        if ($flag_exchange) {
            $flag_exchange = false;
            if ($prize_info->getTypeExchange() == 'RECARGA') {
                // se deja por defecto el envio centralizado
                $url = "http://192.168.10.221/service-container/call/16/phone/502{$phone}/amount/{$prize_amount}/account/10/token/Nzk0Mjc5NjE=/detail/" .
                        urlencode(json_encode($detail));
                $ws_response = $sessionHelper->open_url($url);
                $ws_response = str_replace('""{', '"{', $ws_response);
                $ws_response = str_replace('}""', '}"', $ws_response);
                $resObj = json_decode($ws_response);
                if ($resObj && $resObj->status == "TRUE") {
                    $message = $this->container->getParameter('exito_canje');
                    $message = str_replace("[CREDITS]", "$prize_credits",
                            $message);
                    $message = str_replace("[SALDO]", "$new_credit", $message);
                    $message = str_replace("[PREMIO]", $prize_info->getName(),
                            $message);
                    // Change error boolean
                    $flag_exchange = true;
                } else {
                    $message = $this->container->getParameter('error_canje');
                    $sms_response = $this->container->getParameter(
                            'error_canje_sms');
                    // Change error boolean
                    $flag_exchange = false;
                }
            } else {
                // Booleans for conection
                $isConTrade = false;
                $conCodeError = false;
                $code = null;
                // Need to send sms with code at the end
                if ($prize_info->getTypeExchange() == 'INSTANTANEO-MAX') {
                    $productId = $prize_info->getProductId();
                    $url = "http://192.168.10.221/service-container/call/7/phone/" .
                            $area_code . $phone . "/prize_id/" . $productId .
                            "/provider_id/2/token/NDUyNTE2ODk=/amount/1/detail/" .
                            urlencode(json_encode($detail));
                    $ws_response = $sessionHelper->open_url($url);
                    $res = json_decode($ws_response);
                    if ($res->message == "Success") {
                        $code = $res->pin;
                    }
                    if ($code != null) {
                        // Boolean to know if sms failed
                        $flag_exchange = true;
                        $message = $this->container->getParameter(
                                'exito_canje_codigo');
                        $message = str_replace("[CREDITS]", "$prize_credits",
                                $message);
                        $message = str_replace("[SALDO]", "$new_credit",
                                $message);
                        $message = str_replace("[PREMIO]",
                                $prize_info->getName(), $message);
                        $message = str_replace("[CODE]", $code, $message);
                        $sms_response = $prize_info->getSmsResponse();
                    } else {
                        // Error
                        $message = $this->container->getParameter('error_canje');
                        $sms_response = $this->container->getParameter(
                                'error_canje_sms');
                        $flag_exchange = false;
                    }
                } else 
                    if ($prize_info->getTypeExchange() == 'INSTANTANEO-OMC') {
                        $amount = $prize_info->getValue();
                        $productId = $prize_info->getProductId();
                        $url = 'http://192.168.10.221/service-container/call/9/amount/' .
                                $amount . '/phone/' . $phone .
                                '/token/Nzk0Mjc5NjE=/product_id/' . $productId .
                                "/detail/" . urlencode(json_encode($detail));

                        $response = $sessionHelper->open_url($url);
                        $code = "";
                        $code_arr = json_decode($response, true);
                        if ($code_arr["status"] == "success") {
                            $flag_exchange = true;
                            $code = $code_arr["coupon"];
                            $message = $this->container->getParameter(
                                    'exito_canje_codigo');
                            $message = str_replace("[CREDITS]", "$prize_credits",
                                    $message);
                            $message = str_replace("[SALDO]", "$new_credit",
                                    $message);
                            $message = str_replace("[PREMIO]",
                                    $prize_info->getName(), $message);
                            $message = str_replace("[CODE]", $code, $message);
                            $sms_response = $prize_info->getSmsResponse();
                        } else {
                            // Error
                            $message = $this->container->getParameter(
                                    'error_canje');
                            $sms_response = $this->container->getParameter(
                                    'error_canje_sms');
                            $flag_exchange = false;
                        }
                    } else 
                        if ($prize_info->getTypeExchange() ==
                                'INSTANTANEO-EFECTIVO-INTERBANCO') {
                            $flag_exchange = true;
                            $message = $prize_info->getSmsResponse();
                            $sms_response = $prize_info->getSmsResponse();
                        } else 
                            if ($prize_info->getTypeExchange() ==
                                    'INSTANTANEO-CONECTION' ||
                                    $prize_info->getTypeExchange() ==
                                    'INSTANTANEO-HONDA') {
                                // It's a prize from conection
                                $isConTrade = true;

                                // Validate if there is a code to give (codes
                                // left in inventory)
                                $conCode = $em->getRepository(
                                        'AppBundle:PrizePin')->findOneBy(
                                        array(
                                                'usedBy' => null,
                                                'prizeId' => $prize_id
                                        ));

                                // Mark error from conection codes
                                if (! $conCode) {
                                    // Error on create code from conection
                                    $flag_exchange = false;
                                    $message = $this->container->getParameter(
                                            'error_canje');
                                    $sms_response = $this->container->getParameter(
                                            'error_canje_sms');
                                } else {
                                    $flag_exchange = true;
                                    // Get code from conection
                                    $code = $conCode->getCode();
                                    // Change info of conectionPin
                                    $conCode->setUsedBy($user_id);
                                    $conCode->setUsedAt(new \DateTime());
                                    $conCode->setPhoneDeliveredTo(
                                            $area_code . $phone);
                                    $em->flush();
                                    $message = $this->container->getParameter(
                                            'exito_canje_codigo');
                                    $message = str_replace("[CREDITS]",
                                            "$prize_credits", $message);
                                    $message = str_replace("[SALDO]",
                                            "$new_credit", $message);
                                    $message = str_replace("[PREMIO]",
                                            $prize_info->getName(), $message);
                                    $message = str_replace("[CODE]", $code,
                                            $message);
                                }
                            } else {
                                $secret = md5(date("Y-m-d"));
                                // Use url to get code
                                $token = $this->getParameter("api_token");
                                $amount = (int) $prize_info->getValue();
                                $url = "http://192.168.10.221/service-container/call/3/product_id/" .
                                        $prize_info->getProductId() .
                                        "/amount/{$amount}/phone/" . $area_code .
                                        $phone . "/first_name/" .
                                        urlencode($user_name) .
                                        "/last_name/NA/token/Nzk0Mjc5NjE=/detail/" .
                                        urlencode(json_encode($detail));
                                $res = $sessionHelper->open_url($url);
                                // Default value for is successful response
                                $ts = false;

                                // Try to read JSON from response
                                $resObj = $sessionHelper->findJSONObject($res);
                                if ($resObj !== null) {
                                    // Check that exchange was succesful
                                    $ts = $sessionHelper->isTransactionSuccessful(
                                            $resObj);
                                }

                                if ($ts === false) {
                                    $flag_exchange = false;
                                    $message = $this->container->getParameter(
                                            'error_canje');
                                    $sms_response = $this->container->getParameter(
                                            'error_canje_sms');
                                } else {
                                    // Get code from exchange response
                                    $code = $sessionHelper->getExchangeCode(
                                            $resObj);

                                    // If no code was found, give error
                                    if ($code === null) {
                                        $flag_exchange = false;
                                        $message = $this->container->getParameter(
                                                'error_canje');
                                        $sms_response = $this->container->getParameter(
                                                'error_canje_sms');
                                    } else {
                                        $flag_exchange = true;
                                        $message = $this->container->getParameter(
                                                'exito_canje_codigo');
                                        $message = str_replace("[CREDITS]",
                                                "$prize_credits", $message);
                                        $message = str_replace("[SALDO]",
                                                "$new_credit", $message);
                                        $message = str_replace("[PREMIO]",
                                                $prize_info->getName(), $message);
                                        $message = str_replace("[CODE]", $code,
                                                $message);
                                    }
                                }
                            }
            }
        } else {
            // EN ESTE PUNTO ES QUE NO TENIA PUNTOS SUFICIENTES VERIFICAR SI HAY
            // OTRO MENSAJE U OTRA VALIDACION POR COLOCAR
            $message = str_replace("[CREDITS]", "$available_credits", $message);
        }

        if ($flag_exchange) {
            $usePromoPoints = 0;
            $prize_exchange_arr = $em->getRepository('AppBundle:PrizeExchange')->redeemPrize(
                    $prize_id, $user_id, $pointsToExchange, $channel_exchange,
                    $available_credits);
            $sms_response = $prize_info->getSmsResponse();
        } else {
            if (! $mostrar_mensaje_original) {
                $message = "Tu solicitud esta siendo procesada y sera confirmada en el transcurso del dia.";
            }
        }
        $sms_response = str_replace("[CREDITS]", "$prize_credits", $sms_response);
        $sms_response = str_replace("[SALDO]", "$new_credit", $sms_response);
        $sms_response = str_replace("[POINTS]", "$available_credits",
                $sms_response);
        $sms_response = str_replace("[PREMIO]", $prize_info->getDisplayName(),
                $sms_response);
        $sms_response = str_replace("[CODE]", $code, $sms_response);

        if ($channel_exchange == "SMS") {
            $sessionHelper->send_sms_televida($area_code . $phone, $sms_response,
                    $token);
        } else 
            if ($flag_exchange) {
                // TODO: Obtener el token de parameters
                $token = $this->getParameter("api_token");
                $sessionHelper->send_sms_televida($area_code . $phone,
                        $sms_response, $token);
            }
        return $this->render('@App/Frontend/Staff/getprize.html.twig',
                array(
                        "message" => $message,
                        "channel" => $channel_exchange
                ));
        */

        
        $this->get("session")->set("module_id", 21);

        $prizeId = $request->get("id");
        $channelExchange = $request->get("channel", "WEB");

        $userData = $this->get("session")->get("userData");
        $token = $this->get("session")->get("tokenapp");

        $apiHelper = new ApiHelper();
        
        if(isset($prizeId) && isset($channelExchange)){
                $exchange = $apiHelper->connectServices($this->getParameter("contenedor_3")."register-exchange-prize/$prizeId/$channelExchange", "GET", $token, null);
                
                $exchange = json_decode($exchange);

                if($exchange->status == 'success'){
                        $this->addFlash('success_message', $exchange->msg);
                        return $this->redirectToRoute("backend_staff_redeem");
                } else {
                        $this->addFlash('error_message', $exchange->msg);
                        return $this->redirectToRoute ( "staff_prize_details", ["id" => $prizeId]);
                }
        }

    }

    /**
     *
     * @Route(
     *  "/staff/job_position/children/{id}",
     *  name="staff_sale_job_position_children",
     *  defaults={"id" = null}
     * )
     */
    public function jobPositionChildrenAction (Request $request, Staff $staff)
    {
        $tree = array();

        if ($staff) {
            $em = $this->getDoctrine()->getManager();

            // Get first first children in tree
            $jobPosition = $staff->getJobPosition();

            if ($jobPosition) {
                $tree = $em->getRepository('AppBundle:Staff')->findLowerJobPosition(
                        $staff, array(
                                $jobPosition->getId()
                        ));
            }
        }

        return new JsonResponse($tree);
    }

    /**
     *
     * @Route(
     *  "/staff/promo-detail",
     *  name="staff_promo_detail"
     * )
     */
    public function promoDetailAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Get post parameters
        $params = array();
        $content = $this->get("request")->getContent();
        if (! empty($content)) {
            $params = json_decode($content, true);
        }

        $minMonth = $params['minMonth'];
        $maxMonth = $params['maxMonth'];
        $rootId = $params['root'];

        // Use last elemet of array to get selecte children
        $staffInfo = $params['staffInfo'][sizeof($params['staffInfo']) - 1];

        if (! isset($staffInfo['children'])) {
            $staffInfo['children'] = array();
        }

        $promoDetail = $em->getRepository("AppBundle:Staff")->getPromoDetail(
                $staffInfo['children'], $minMonth, $maxMonth);

        // Return JSON with new info
        return new JsonResponse(
                array(
                        'status' => 200,
                        'promoDetail' => $promoDetail
                ));
    }

    public function headerPointsAction (Request $request)
    {
            
        $userData = $this->get("session")->get("userData");
        $token = $this->get("session")->get("tokenapp");

        $apiHelper = new ApiHelper();

        $service = $apiHelper->connectServices($this->getParameter("contenedor_3")."get-staff-points-header", "GET", $token, null);

        $service = json_decode($service);
        
        if($service->status == 'success'){
		$pointDetail = $service->data;
	} else {
		$pointDetail = null;
        }

        // replace this example code with whatever you need
        return $this->render('@App/Frontend/Staff/header_points.html.twig',
                array(
                        "pointDetail" => $pointDetail
                ));
    }

    /**
     * Adds sales for plot to an already existing sales for plot
     *
     * @param array $oldSales
     *            already existing sales for plot
     * @param array $newSales
     *            sales to add
     * @return array sum by month of $oldSales and $newSales
     */
    private function addSalesForPlot ($oldSales, $newSales)
    {
        foreach ($newSales as $newSale) {
            $oldSales = $this->addSaleForPlot($oldSales, $newSale);
        }

        return $oldSales;
    }

    /**
     * Adds a sale for plot to an alredy existing set of sales for plot
     *
     * @param array $sales
     *            already existing sales
     * @param array $add
     *            array with ['mes', 'total_ventas']
     * @return array $existing array with added sale from $add
     */
    private function addSaleForPlot ($sales, $add)
    {
        foreach ($sales as $key => $sale) {
            if ($sale['mes'] == $add['mes']) {
                $sale['total_ventas'] += $add['total_ventas'];

                $sales[$key] = $sale;
                return $sales;
            }
        }

        // This month wasn't found
        array_push($sales, $add);

        return $sales;
    }

    /**
     * Adds sku for already existing skus
     *
     * @param array $oldSkus
     *            already existing skus for plot
     * @param array $newSkus
     *            skus to add
     * @return array sum by month of $oldSkus and $newSkus
     */
    private function addSkuSales ($oldSkus, $newSkus)
    {
        foreach ($newSkus as $newSku) {
            $oldSkus = $this->addSkuSale($oldSkus, $newSku);
        }

        return $oldSkus;
    }

    /**
     * Adds a sku to an alredy existing set of skus
     *
     * @param array $skus
     *            already existing skus
     * @param array $add
     *            array with ['total_venta', 'total_puntos', 'cc']
     * @return array $existing array with added sku from $add
     */
    private function addSkuSale ($skus, $add)
    {
        foreach ($skus as $key => $sku) {
            if ($sku['cc'] == $add['cc']) {
                $sku['total_venta'] += $add['total_venta'];
                $sku['total_puntos'] += $add['total_puntos'];

                $skus[$key] = $sku;
                return $skus;
            }
        }

        // This month wasn't found
        array_push($skus, $add);

        return $skus;
    }
}
