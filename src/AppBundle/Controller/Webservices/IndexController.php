<?php
namespace AppBundle\Controller\Webservices;
/*
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type,x-prototype-version,x-requested-with');
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
*/

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\RegisterPending;
use AppBundle\Entity\StaffCode;
use AppBundle\Entity\Staff;
use AppBundle\Entity\InvoiceWhatsapp;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\SessionHelper;
use Doctrine\ORM\Query\AST\Functions\SubstringFunction;

class IndexController extends Controller
{



	/**
    *
    * @Route("/ws/register-code", name="ws_register_code")
    */
    public function registeCodeAction (Request $request)
    {
	    $staff_id = $request->get('staff_id', 0);
        $code = $request->get('code', 0);
        $store_id = $request->get('store_id', 0);
        $bill_number = $request->get('bill_number', 0);
	    
	    if($staff_id <= 0  || $code == "" || $store_id<=0 || $bill_number == ""  ){
			return new JsonResponse(array(
			    'status' => "error",
			    'code' => "code, staff_id, store_id or bill_number invalid."
			));
	    }	    
	    $em = $this->getDoctrine()->getManager();
        // buscar codigo de bar / restaurante
        $staff = $em->getRepository("AppBundle:Staff")->findOneBy(
            array(
                    "staffId" => $staff_id
            ));
        
        $code_status = $em->getRepository("AppBundle:CodeStatus")->findOneBy(
            array(
                    "codeStatusId" => 2
            ));
        
         
        $store = $em->getRepository("AppBundle:Store")->findOneBy(
            array(
                    "id" => $store_id           
                ));            
            
         $code_status_available = $em->getRepository("AppBundle:CodeStatus")->findOneBy(
            array(
                    "codeStatusId" => 1
            ));
                
        $staff_code = $em->getRepository("AppBundle:StaffCode")->findOneBy(
            array(
                    "code" => $code,           
                    "codeStatus" => $code_status_available           
                ));    
                
	    if($staff_code){
		    
		    
		    $prize_info = $em->getRepository("AppBundle:Prize")->getPrize($staff->getStaffId(), $staff->getCountry()->getId());
			$prize = $em->getRepository("AppBundle:Prize")->findOneBy(
            array(
                    "id" => $prize_info["id"]     
                 ));         

		    //$staff_code = new StaffCode();            
	        $staff_code->setStaff($staff);
	        $staff_code->setCode($code);
	        $staff_code->setStore($store);
	        $staff_code->setPrize($prize);
	        $staff_code->setBillNumber($bill_number);
	        $staff_code->setCodeStatus($code_status);
	        $staff_code->setCreatedAt(new \DateTime());			
			$em->persist($staff_code);
	        $em->flush();
	        
	        
	        
	        $send_sms = new SessionHelper();
			
			$token = $this->getParameter("api_token");
			$phone= $staff->getPhone();
			$country= $staff->getCountry()->getId();
			
		    $message = str_replace("[CODE]", $code, $prize->getMessage());
		    $send_sms->send_sms($phone,urlencode($message),$token, $country);
			
			return new JsonResponse(array(
			    'status' => "ok",
			    'code' => $prize_info
			));
			

	        
		}else{
			return new JsonResponse(array(
			    'status' => "error",
			    'code' => "Codigo invalido o repetido."
			));
		}	
           
                        
		return new JsonResponse(array(
			    'status' => "ok",
			    'code' => "code or staff_id invalid."
			));
		exit;
	}


    /**
     *
     * @Route("/ws/client-subscription", name="ws_get_client_subscription")
     */
    public function getClientAction (Request $request)
    {
        
        // variables que nos envia kannel
        $msisdn = $request->get('phone', 0);
        $content = $request->get('msg', 0);
        $content = trim($content);
        
        $myfile = fopen($this->getParameter("ws_log_file"), "a+");
        fwrite($myfile, "INICIO \r\n");
        fwrite($myfile, '@Route("/ws/client-subscription", name="ws_get_client_subscription") \r\n');
        fwrite($myfile, "GET: " . print_r($_GET, true));       
        fwrite($myfile, "srtlen msisdn: $msisdn " . strlen($msisdn) . "\r\n");
        fwrite($myfile, "srtlen content: $content " . strlen($content) . "\r\n");
        $txt = "FIN \r\n\n";
        fwrite($myfile, $txt);
        fclose($myfile);

       
        //
        $send_sms = new SessionHelper();
        $token = $this->getParameter("api_token");

        if ($msisdn) {
            // validar si viene con codigo de area
            if (strlen($msisdn) == 12) {
                $msisdn = substr($msisdn, 4, strlen($msisdn));
            }

            $phone = '502' . $msisdn;

            if ($content) {                               
                
                $em = $this->getDoctrine()->getManager();
                // buscar codigo de bar / restaurante
                $pos = $em->getRepository("AppBundle:PointOfSale")->findOneBy(
                        array(
                                "pointOfSaleInnerId" => $content,
                                "status" => 1
                        ));
                if ($pos) {                                       
                    
                    $s1 = $em->getRepository('AppBundle:Staff')->findOneBy(
                            array(
                                    'phoneMain' => $msisdn,
                                    "staffStatus" => 2
                            ));

                    $s2 = $em->getRepository('AppBundle:RegisterPending')->findOneBy(
                            array(
                                    'phoneMain' => $msisdn,
                                    "status" => 2
                            ));

                    if ($s1) {
                        // phone already in database
                        $send_sms->send_sms_televida($phone,
                                urlencode(
                                        $this->getParameter(
                                                'registro_sms_existente')),
                                $token);
                        $this->printForDebug(
                                $this->getParameter('registro_sms_existente'));
                    } else {
                        // guardar registro en register pending
                        // tipo de registro bartender / mesero
                        $data = array();
                        $data['job_position'] = 3;
                        // datos demograficos se asume guatemala - ciudad capital
                        $data['city'] = 77;
                        $data["state"] = 1;
                        $data['city_company'] = 77;
                        $data['state_company'] = 1;
                        
                        
                        $dataPending = new RegisterPending();
                        //$dataPending->setName($data['name'] . ' ' . $data['last_name']);
                        // $dataPending->setTaxIdentifier($data['nit']);
                        //$dataPending->setCitizenId($data['citizen_id']);
                        // $dataPending->setGender($data['gender']);
                        // $dataPending->setBirthdate(new
                        // \DateTime($data['birthdate']));
                        // $dataPending->setPhoneSecondary($data['secundary_phone_number']);
                        $dataPending->setPhoneMain($msisdn);
                        // $dataPending->setEmail($data['email']);
                        // $dataPending->setZone($data['zone']);
                        // $dataPending->setAddress1($data['address']);
                        $dataPending->setJobPositionId($data['job_position']);
                        $dataPending->setCreatedAt(new \DateTime());
                        $dataPending->setStatus(1);
                        $city = $this->getDoctrine()
                        ->getRepository('AppBundle:City')
                        ->findById($data['city']);
                        $state = $this->getDoctrine()
                        ->getRepository('AppBundle:State')
                        ->findByStateId($data['state']);
                        $businesCity = $this->getDoctrine()
                        ->getRepository('AppBundle:City')
                        ->findById($data['city_company']);
                        $businesState = $this->getDoctrine()
                        ->getRepository('AppBundle:State')
                        ->findByStateId($data['state_company']);
                        
                        $dataPending->setCity($city[0]);
                        $dataPending->setState($state[0]);
                        $dataPending->setPointOfSale($pos);
                        $dataPending->setCountry($state[0]->getCountry());
                        $dataPending->setRegisterType("SMS");
                        
                        // nuevos campos
                        $dataPending->setBusinessCity($businesCity[0]->getName());
                        $dataPending->setBusinessState($businesState[0]->getName());
                        // $dataPending->setBusinessZone($data['businessZone']);
                        // $dataPending->setBusinessAddress($data['businessAddress']);
                        // $dataPending->setGroupName($data['groupName']);
                        // $dataPending->setBusinessName($data['businessName']);
                        $em->persist($dataPending);
                        $em->flush();
                        $send_sms->send_sms_televida($phone,
                                urlencode($this->getParameter('sms_exito_revisado')), $token);
                        $this->printForDebug($this->getParameter('sms_exito_revisado'));
                                              
                    }
                } else {
                    
                    // no se encontro el point of sale, enviar mensaje de error;
                    $send_sms->send_sms_televida($phone,
                            urlencode($this->getParameter('error_codigo_rest')), $token);
                    $this->printForDebug($this->getParameter('error_codigo_rest'));
                    
                }
            } 
        }
        
        die;
    }
    
    private function printForDebug($string) {
        echo $string;
    }


	 /**
     *
     * @Route("ws/generate-code", name="ws_generate_code")
     */
    public function generateCodeAction (Request $request)
    {
	    
	    
        $staff_id = $request->get('staffId', 0);
		$campaign_id = $request->get('campaignId', 0);
		
        $em = $this->getDoctrine()->getManager();

		if(!$staff_id){
			 return new JsonResponse(array(
		            'status' => "error",
		            'data' => "staffId invalido."
		        ));			
		}	
		
		if(!$campaign_id){
			 return new JsonResponse(array(
		            'status' => "error",
		            'data' => "campaign invalido."
		        ));			
		}	
        $staff = $em->getRepository("AppBundle:Staff")->findOneBy(
        	array(
            	"staffId" => $staff_id
			));
		$campaign = $em->getRepository("AppBundle:Campaign")->findOneBy(
        	array(
            	"campaignId" => $campaign_id
			));	
		
			
		$prefix = "S";
		$code_len = 5;
		$codes = 30000;
		$codes_cont =0 ;
		$matriz = array(); 
		$ab  = array('a','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
		$ab2  = array('a','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
		$ab3  = array('a','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
		$ab4  = array('a','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
		$ab5  = array('a','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
		
		foreach($ab as $value){
			for($i = 1 ; $i <= $code_len ; $i++ ){
				$matriz[$value][$i] = rand(-2,3);
			}
		}
		shuffle($ab);
		shuffle($ab2);
		shuffle($ab3);
		shuffle($ab4);
		shuffle($ab5);		
		$i = 0;
		$sum=0;
		foreach($ab as $value){
			foreach($ab2 as $value2){
				foreach($ab3 as $value3){
					foreach($ab4 as $value4){
						foreach($ab5 as $value5){
							if($i == $codes ){
								break;
								exit;
							}
							$i++;
							$array_code = array();
							$array_code = array($value,$value2,$value3,$value4,$value5);
							shuffle($array_code);
							//$code = $value.$value2.$value3.$value4.$value5.$value6;
							$code = implode("", $array_code);
					
							$length = strlen($code);
							$last_letter = "";
							for ($i=1; $i<=$length; $i++) {
							    $position = $i-1;
							    $letter = $code[$position];
							 
							    $sum = $sum + $matriz[$letter][$i];
							    if( substr_count($code, $letter) > 2){
							    	$sum = $sum + 15;
							    }
							    if($last_letter == $letter){
							    	$sum = $sum + 15;
							    }
								$last_letter = $letter;    
							}
							if($sum == 0 and strlen($code) == $code_len ){
									$code = $prefix.$code;
									$code = strtoupper($code);
									$codes_cont++;
									$validate_code = $em->getRepository('AppBundle:StaffCode')->validateUniqueCode($code);
									$code_status= $em->getRepository('AppBundle:CodeStatus')->findOneBy(array("codeStatusId" => "1"));
									//$validate_code = $em->getRepository('AppBundle:StaffCode')->validateUniqueCode("CODE 1");
									if($validate_code <= 0){
										$staff_code = new StaffCode();
										$staff_code->setCode($code);
										$staff_code->setStaff($staff);
										$staff_code->setCampaign($campaign);
										$staff_code->setCodeStatus($code_status);
										$staff_code->setCreatedAt(new \DateTime());
										$em->persist($staff_code);
										$em->flush();
										
										$send_sms = new SessionHelper();
										$token = $this->getParameter("api_token");
										$smsResponse = "Sherwin-Williams informa que su CODIGO  de Promo es: $code.";
										//ENVIO DE MENSAJE
										$send_sms->send_sms_claro($staff->getPhone(), $smsResponse, $token);
								        

										return new JsonResponse(array(
										    'status' => "ok",
										    'code' => "$code"
										));
										exit;	
									}
									/*echo "codigo no valido";
									echo $code;
									echo "<br>";
									*/
									if($codes_cont == 20){
										$prefix = "W";
									}elseif($codes_cont == 40){
										$prefix = "H";
									}elseif($codes_cont == 1000){
										return new JsonResponse(array(
										    'status' => "error",
										    'data' => "llego a limite de intentos."
										));			
										exit;
									}
									if($codes == $codes_cont){
										mysql_close($conn);
										exit;
									}
							}
							$last_letter = "";
							$sum = 0;
						}
						if($i == $codes ){
							break;
							exit;
						}
					}
					if($i == $codes ){
						break;
						exit;
					}
				}
			
				if($i == $codes ){
					break;
					exit;
				}		
			}
			if($i == $codes ){
				break;
				exit;
			}		
		}	
		
		
    	exit; 
	}

    /**
     * @Route("/ws/upload-picture", name="ws_upload_picture")
     */
    public function uploadPictures(Request $request) {
        
        $phone = $_POST['phone'];
    	$fullPath = "/home/nelson/EBCLOSION/symfony/lala/web/uploads/imgs/";
		$imageDataExt = explode(".",basename($_FILES['file']['name']));
		$x = sizeof($imageDataExt) - 1;
		$imageDataExt = $imageDataExt[$x];
		$fileName = md5(uniqid()).'.'.$imageDataExt;
		$uploaded = $fullPath . $fileName;
        $isSaved = move_uploaded_file($_FILES['file']['tmp_name'], $uploaded);
		if ($isSaved) {
			//Se procede a validar si el phone eexiste en staff
			$phoneData = $this->getDoctrine()->getRepository("AppBundle:Staff")->findOneBy(array("phone"=>$phone));
			if ($phoneData) {
				//$staffId = $phoneData->getStaffId();
				$mensaje = "Si existe";
				$staffObj = $phoneData;
				$recurrent = 1;
			}else{
				$countryObj = $this->getDoctrine()->getRepository("AppBundle:Country")->findOneBy(array("id"=>1));
				$em = $this->getDoctrine()->getManager();
				$newStaff = new Staff();
				$newStaff->setPhone($phone);
				$newStaff->setCreatedAt(new \DateTime());
				$newStaff->setCountry($countryObj);
				$em->persist($newStaff);
				$em->flush();
				$staffObj = $newStaff;
				//$staffId = $newStaff->getStaffId();
				$mensaje = "No existe";
				$recurrent = 0;
			}
			$em = $this->getDoctrine()->getManager();	
			$statusObj = $this->getDoctrine()->getRepository("AppBundle:MainStatus")->findOneBy(array("name"=>"Pendiente"));
			$invoiceWs = new InvoiceWhatsapp();
			$invoiceWs->setStaff($staffObj);
			$invoiceWs->setImageName($fileName);
			$invoiceWs->setCreatedAt(new \DateTime());
			$invoiceWs->setStatus($statusObj);
			$invoiceWs->setRecurrent($recurrent);
			$em->persist($invoiceWs);
			$em->flush();

			return new JsonResponse(array(
	            'status' => "Success",
	            'message' => "Imagen Recibida!",
	            'existe' => $mensaje
	        ));
	        
		} else {
			
			return new JsonResponse(array(
	            'status' => "Error",
	            'message' => "La imagen no pudo ser guardada"
	        ));   
		}        		
	}

    /**
     * @Route("/ws/get-responses", name="ws_get_responses")
     */
    public function getResponses(Request $request) {
    	
    	$mtoSend = $this->getDoctrine()->getRepository("AppBundle:InvoiceWhatsapp")->getMessages();
    	
    	return new JsonResponse(array(
	            'status' => "ok",
	            'message' => $mtoSend
	        ));
	}

    /**
     * @Route("/ws/update-st-msg", name="ws_update_st_msg")
     */
    public function updateStMsg(Request $request) {
    	
    	$scid = $_POST['scid'];
    	$em = $this->getDoctrine()->getManager();
		try {
			$stIdObj = $em->getRepository("AppBundle:StaffCode")->findOneBy(array("staffCodeId"=>$scid));
	    	$stIdObj->setWhatsappStatus('enviado');
	    	$em->persist($stIdObj);
	    	$em->flush(); 
	    	$sts = 'ok';
		 } catch (Exception $e) {
		 	$sts = 'error';
		}
    	return new JsonResponse(array(
	            'status' => $sts
	        ));
	}


}

