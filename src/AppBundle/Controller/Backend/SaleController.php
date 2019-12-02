<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\SaleReverse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Sale;
use AppBundle\Entity\SaleMixtoListo;
use AppBundle\Entity\Staff;
use AppBundle\Entity\SaleStaff;
use AppBundle\Entity\LogParser;
use AppBundle\Entity\ParserRegister;
use AppBundle\Entity\PointOfSale;
use AppBundle\Form\PointOfSaleType;
use AppBundle\Form\SaleFileType;
use AppBundle\Form\SaleType;
use AppBundle\Form\SaleStaffCancellType;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\SaleReverseType;
use AppBundle\Entity\SmsIncoming;


class SaleController extends Controller
{
    private $moduleId = 2;

    /**
     * @Route("/backend/sale", name="backend_sale")
     */
    public function indexAction(Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $em = $this->getDoctrine()->getManager();

        // Get search parameters
        $skuFilterString = $request->get('sku');
        $skuCode = $request->get('code');
        $innerId = $request->get('innerId');
        $issuedAt = $request->get('issuedAt');
        $invoice = $request->get('invoice');
        $isAssigned = $request->get('isAssigned');

        // Check if is assigned is correctly send
        if ($isAssigned === '1') {
            $isAssigned = true;
        }
        else if ($isAssigned === '0') {
            $isAssigned = false;
        }
        else {
            $isAssigned = NULL;
        }

        $sales = $em->getRepository ('AppBundle:Sale')
            ->search(
                $skuCode,
                $skuFilterString,
                $innerId,
                $issuedAt,
                $invoice,
                $isAssigned
            );

        // Paginate sales
        $paginator = $this->get ( 'knp_paginator' );

        $pagination = $paginator->paginate (
            $sales,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows_large" )
        );
        
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        // Create cancell form
        $form = NULL;
        if ($this->getUser()->getUserRole()->getId() == 1) {
            $form = $this->createForm (new SaleStaffCancellType (), new SaleStaff());
            $form = $form->createView();
        }

        return $this->render ( '@App/Backend/Sale/index.html.twig', array(
            "sales" => $pagination,
            'noFilterPath' => 'backend_sale',
            "permits" => $mp,
            'listPath' => '@App/Backend/Sale/list.html.twig',
            'form' => $form
        ) );
    }

    /**
     * @Route("/backend/sale/upload", name="backend_sale_upload")
     */
    public function uploadAction(Request $request)
    {
        // Form to upload file
        $saleFile = new Sale();
        $form = $this->createForm ( new SaleFileType (), $saleFile );
        return $this->render(
            '@App/Backend/Sale/upload.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
    
    
    /**
     * @Route("/backend/sale/upload_v2", name="backend_sale_upload_v2")
     */
    public function uploadV2Action(Request $request)
    {
        $this->get("session")->set("module_id", 28);
        
        // Form to upload file
        $saleFile = new Sale();
       
        $form = $this->createForm ( new SaleFileType (), $saleFile);
        
        $concreteType = $this->getDoctrine()->getRepository("AppBundle:ConcreteType")->findAll();
        
        
        return $this->render(
                '@App/Backend/Sale/uploadV2.html.twig',
                array(
                        'form' => $form->createView(),                        
                        "concreteType" => $concreteType
                )
                );
    }

    
    /**
	 *  @Route("/backend/sale/personal", name="backend_sale_personal")
	 */
	public function salePersonalAction(Request $request)
	{
		
		$counter = array();
		return $this->render ( '@App/Backend/Sale/personal.html.twig', array(
           "counter" => $counter
        ));
	}
    
    /**
     * @Route("/backend/sale/save_upload", name="backend_sale_save_upload")
     */
    public function saveUploadAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Form to upload file
        $saleFile = new Sale();
        $form = $this->createForm ( new SaleFileType (), $saleFile );

        $error = true;
        $errors = array();

        // Process to save file
        $form->handleRequest($request);
        $file = $saleFile->getFile();
        if ($form->isSubmitted() && $form->isValid()) {
                        
            $file = $saleFile->getFile();
                        
            
            // Move file
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();

            // Move the file to the directory where excels are stored
            $fileDir = $this->getParameter('sale_upload_folder');
            $file->move(
                $fileDir,
                $fileName
            );

            // Check that file was uploaded
            $filePath = $fileDir . '/' . $fileName;
            if (file_exists($filePath)) {
                try {
                                                     

                    $sales = $this->readExcelFile($filePath, $fileName);                    
                    $error = true;
                    
                    
                    // Keep type of elements to persist
                    $type = null;
                    $logParserType = 'AppBundle\Entity\LogParser';
                    $saleType = 'AppBundle\Entity\Sale';

                    $countReverse = 0;
                    
                    foreach ($sales as $key => $sale) {
                        // Save sale
                        if (get_class($sale) == $logParserType) {

                            array_push($errors, "Fila No. $key, " . $sale->getLogText());
                        } else {
                               
                               
                          
                            // verificar si el precio de la venta es negativo para guardarlo en reversa
                            if ($sale->getPrice() < 0) {
                                /*
                                $sr = new SaleReverse();
                                $sr->setCreatedAt(new \DateTime());
                                $sr->setSkuCode($sale->getSkuCode());
                                $sr->setStatus("INGRESADO");
                                $em->persist($sr);

                                $countReverse++;
                                */

                            } else {
                                // si no es negativo realizar el save en sales
                                $em = $this->getDoctrine()->getManager();
                                $sale->setIsCancelled(0);
                                $sale->setIssuedAt(new \DateTime());
                                $em->persist($sale);
                                                               
                                
                                // save de sms_incoming
                                $smsi = new SmsIncoming();
                                $smsi->setAlreadyParsed(0);
                                $smsi->setCreatedAt(new \DateTime());
                                $smsi->setIsCancelled(0);
                                //$smsi->setPhone($sale->getClientPhone());
                                $smsi->setSmsString($sale->getSkuCode());
                                
                                //$staff = $em->getRepository('AppBundle:Staff')->findOneBy(array("phoneMain" => $sale->getClientPhone(), "staffStatus" => 2));
                                //$smsi->setStaff($staff);
                                $em->persist($smsi);
                                


                            }
                        }


                    }


                    if (sizeof($errors) == 0) {
                        $error = false;
                    }
                    $em->flush();
                } catch (Exception $e) {
                    echo $e->getMessage(); 
                    die;
                }
            }
        }
        else {
            
            array_push($errors, $this->getParameter('error_form'));
        }



        if ($error) {

            $errorsMess = join("<br>", $errors);
            $errorsMess = $this->container->getParameter ( 'error_sale_upload' ) .
                "<div class='list-error'> " .
                    $errorsMess .
                "</div>";
            
            array_unshift(
                $errors,
                $this->container->getParameter ( 'error_sale_upload' )
            );

            // Error message
            $this->addFlash(
                'error_message',
                $errorsMess
            );
        }
        else {

            // Success message
            $this->addFlash(
                'success_message',
                $this->container->getParameter ( 'exito' )
            );

            if ($countReverse)
                $this->addFlash(
                    'alert_message',
                    sprintf($this->container->getParameter ( 'add_reverse' ), $countReverse)
                );

        }
                    
        return $this->redirectToRoute('backend_sale_upload_v2');
            
           
    }

    /**
     * @Route("/backend/sale/not-assigned", name="backend_not_assigned_sale")
     */
    public function notAssignedAction(Request $request)
    {
        $this->get("session")->set("module_id", 22);

        $em = $this->getDoctrine()->getManager();

        // Get search parameters
        $skuFilterString = $request->get('sku');
        $skuCode = $request->get('code');
        $innerId = $request->get('innerId');
        $sales = $em->getRepository ( 'AppBundle:Sale')->notInSearch($skuCode, $skuFilterString, $innerId);
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate (
            $sales,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows_large" )
        );

        $mp = EbClosion::getModulePermission(22, $this->get("session")->get("userModules"));
        return $this->render ( '@App/Backend/Sale/index.html.twig', array(
                "sales" => $pagination,
                'noFilterPath' => 'backend_not_assigned_sale',
                "permits" => $mp,
                'listPath' => '@App/Backend/Sale/not_assigned_list.html.twig'
        ) );
    }

    /**
     * @Route(
     *  "/backend/sale/staff/{id}",
     *  name="backend_sale_staff",
     *  requirements={"id": "\d+"}
     * )
     */
    public function staffAction(Request $request, Staff $staff)
    {
        // Get parameters for serach
        $issuedAt = $request->get('issuedAt');
        $skuCode = $request->get('skuCode');
        $skuCategory = $request->get('skuCategory');
        $cc = $request->get('cc');
        $points = $request->get('points');
        $invoiceNumber = $request->get('invoiceNumber');

        $em = $this->getDoctrine()->getManager();
        $saleStaffs = $em->getRepository('AppBundle:SaleStaff')->getByStaff(
            $issuedAt, $skuCode, $skuCategory, $cc, $points, $invoiceNumber, $staff
        );

        // Transform to remove objects
        $saleStaffsAr = array();
        foreach ($saleStaffs as $saleStaff) {
            $saleStaffsAr[] = array(
                $saleStaff['issuedAt']->format('Y-m-d H:i'),
                $saleStaff['skuCode'],
                $saleStaff['skCategory'],
                $saleStaff['cc'],
                $saleStaff['points'],
                $saleStaff['invoiceNumber'],
            );
        }

        return new JsonResponse(array(
            'status' => 200,
            'data' => $saleStaffsAr
        ));
    }

    public function editAction(Request $request, Sale $sale) {
        if ($sale) {
            $form = $this->createForm (new SaleType (), $sale);
            $form->handleRequest ( $request );
            if ($form->isSubmitted ()) {
                if ($form->isValid ()) {
                    $error = $this->validateEdit($sale);

                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        $user = $this->edit($sale);
                        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                    }
                } else {
                    // Error validation
                    $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
                }
            }
            return $this->render ( '@App/Backend/Sale/edit.html.twig', array(
                    "form" => $form->createView ()
            ) );
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }
        return $this->redirectToRoute ( "backend_sale" );
    }

    public function readExcelOldFile($path, $fileName) {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($path);

        // Needed
        $em = $this->getDoctrine()->getManager();
        $sales = array();
        $errors = array();

        // Error stuff
        $errorType = $em->getRepository('AppBundle:ParserType')->findOneByParserTypeId(1);
        $user = $this->getUser();

        // Create Parser Register per file
        $parserRegister = new ParserRegister();
        $parserRegister->setFileName($fileName);
        $parserRegister->setCreatedAt(new \DateTime());
        $parserRegister->setCreatedBy($user);
        $parserRegister->setParserType($em->getReference('AppBundle\Entity\ParserType', 1));

        // fallo el save en cascada, se guarda al setear valores
        $em->persist($parserRegister);
        $em->flush();

        foreach ($phpExcelObject ->getWorksheetIterator() as $worksheet) {
            // Every worksheet
            foreach ($worksheet->getRowIterator() as $row) {
                // Every row of this worksheet
                // Each row has a sale
                $sale = new Sale();
                $sale->setCreatedBy($user->getId());
                $sale->setParserRegister($parserRegister);

                $cellIterator = $row->getCellIterator();

                // Loop all cells, even if it is not set
                $cellIterator->setIterateOnlyExistingCells(false);
                $pointOfSale = 0;
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell)) {
                        // Every valid cell of this row

                        // Name of column
                        $col = $cell->getColumn();


                        // Row number
                        $row = $cell->getRow();


                        // Value
                        $value = $cell->getValue();

                        // se agrego la validacion if $value ya que esta tomando rows con valor nulo y devuelve error
                        if ($row >= 2 && $value) {
                            
                            

                            if ($col == 'A') {
                                
                                // buscar filtergroup
                                $fg = $em->getRepository('AppBundle:FilterGroup')->findOneByFilterGroupId($value);
                                if (! $fg) {
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'No se encontr&oacute; el c�digo de material');
                                    break;
                                }
                                else {
                                    // set filter string
                                    $sale->setFilterGroup($fg);
                                    // set sku values
                                    $sku = $em->getRepository('AppBundle:Sku')->findOneBySkuId(1);
                                    $sale->setSku($sku);
                                    $sale->setSkuFilterString($sku->getSkuFilterString());
                                }                              
                                                                
                                
                            }
                            else if ($col == 'B') {
                                // Id of point of sell
                                $pointOfSale = $em->getRepository('AppBundle:PointOfSale')->findOneByPointOfSaleInnerId(trim($value));

                                if (!$pointOfSale) {
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                        ->createSaleLog($user, 'No se encontr&oacute; el punto de venta');
                                    break;
                                }
                                else {
                                    $sale->setPointOfSale($pointOfSale);
                                }
                            }
                            else if ($col == 'C') {
                                // Price of sale
                                $sale->setPrice($value);
                            }
                            else if ($col == 'D') {
                                // Client name of sale
                                $sale->setClientName($value);
                            }
                            else if ($col == 'E') {
                                // Client phone
                                //find client by phone number
                                $staff = $em->getRepository('AppBundle:Staff')->findOneBy(array("phoneMain" => trim($value), "staffStatus" => 2));
                                if (!$staff) {
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'El n&uacute;mero de tel&eacute;fono del cliente no esta registrado en el sistema o se encuentra inactivo.');
                                    break;
                                } else {
                                    $sale->setClientPhone($value);
                                }
                            }
                            else if ($col == 'F') {

                                // agregar validacion para valores negativos
                                
                                
                                if ($value && is_numeric($value)) {
                                                                        
                                    $sale->setQuantity($value);
                                    // Create error
                                    
                                } else { 
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'El campo "Cantidad" es incorrecto');
                                    break;
                                }
                            }
                            else if ($col == 'G') {
                                // Client phone
                                // Try to convert to date assuming the cell is a string
                                try {
                                    if(\PHPExcel_Shared_Date::isDateTime($cell)) {
                                        $factDate = new \DateTime("@".\PHPExcel_Shared_Date::ExcelToPHP($value)); 
                                    }
                                    else {
                                        $factDate = new \DateTime($value);
                                    }
                                }
                                catch (\Exception $e) {
                                    $factDate = NULL;
                                }

                                // Get right now date to compare
                                $now = new \DateTime();

                                if ($factDate == NULL || $factDate > $now) {
                                    // Error, date can't be from the future
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                        ->createSaleLog($user, 'Fecha de facturaci&oacute;n incorrecta');
                                    break;
                                }
                                else {
                                    $sale->setIssuedAt($factDate);
                                }
                            }
                            else if ($col == 'H') {                                
                                
                                // validar si la factura ya fue registrada
                                $invoice = $em->getRepository('AppBundle:Sale')->findOneBy(array('pointOfSale' => $pointOfSale,'invoiceNumber' => $value));
                                if (!$invoice) {
                                    $sale->setSkuCode($value."-".substr(uniqid(), -8));
                                    $sale->setInvoiceNumber($value);
                                } else {
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'El numero de factura ingresado ya existe para ese punto de venta.');
                                    break;
                                }
                            } else if ($col == 'I') {
                                // Id of point of sell
                                $cc = $em->getRepository('AppBundle:ConstructionCategory')->findOneByConstructionCategoryId(trim($value));
                                
                                if (!$cc) {
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'No se encontr&oacute; el C&oacute;digo de Construcci&oacute;n');
                                    break;
                                }
                                else {
                                    $sale->setConstructionCategory($cc);
                                }
                            } else if ($col == 'J') {
                                if ($value) {
                                    $sale->setCupon($value);
                                }
                            }
                        }
                    }
                }


                
                // After each row add to sale to sales
                if ($sale->isValid()) {
                    
                    array_push($sales, $sale);
                }
                
                

            }
        }

        if (sizeof($errors) > 0) {
            return $errors;
        }

        return $sales;
    }
    
    public function readExcelFile($path, $fileName) {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($path);
        
        // Needed
        $em = $this->getDoctrine()->getManager();
        $sales = array();
        $errors = array();        
        
        // Error stuff
        //$errorType = $em->getRepository('AppBundle:ParserType')->findOneByParserTypeId(1);
        $user = $this->getUser();
        
        // Create Parser Register per file
        $parserRegister = new ParserRegister();
        $parserRegister->setFileName($fileName);
        $parserRegister->setCreatedAt(new \DateTime());
        $parserRegister->setCreatedBy($user);
        $parserRegister->setParserType($em->getReference('AppBundle\Entity\ParserType', 1));
        
        // fallo el save en cascada, se guarda al setear valores
        $em->persist($parserRegister);
        $em->flush();
        
        
        foreach ($phpExcelObject ->getWorksheetIterator() as $worksheet) {
            // Every worksheet            
            foreach ($worksheet->getRowIterator() as $row) {
                // Every row of this worksheet
                // Each row has a sale
                
                
                $cellIterator = $row->getCellIterator();
                
                // Loop all cells, even if it is not set
                $cellIterator->setIterateOnlyExistingCells(false);
                $invoiceNumberRow = ""; 
				$transaction = array();
				
                foreach ($cellIterator as $cell) {
                    if (!is_null($cell)) {
                        // Every valid cell of this row
                        
                        // Name of column
                        $col = $cell->getColumn();                        
                        
                        // Row number
                        $row = $cell->getRow();                        
                        
                        // Value
                        $value = $cell->getValue();
                        // se agrego la validacion if $value ya que esta tomando rows con valor nulo y devuelve error
                        if ($row >= 2 && $value) {                                                        
                            
                            if ($col == 'A') {
                                                                
                                // FECHA Y HORA DE COMPRA 
                                try {
                                    if(\PHPExcel_Shared_Date::isDateTime($cell)) {
                                        $factDate = new \DateTime("@".\PHPExcel_Shared_Date::ExcelToPHP($value . " 12:00:00"));
                                    }
                                    else {
                                        $factDate = new \DateTime($value . " 12:00:00");
                                    }
                                }
                                catch (\Exception $e) {
                                    $factDate = NULL;
                                }
                                
                                // Get right now date to compare
                                $now = new \DateTime();
                                
                                if ($factDate == NULL || $factDate > $now) {
                                    // Error, date can't be from the future
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'Fecha y hora de compra incorrecta');
                                    break;
                                }
                                else {
	                                $transaction["factDate"] = $factDate;
                                    //$sale->setIssuedAt($factDate);
                                }
                                
                            }
                            else if ($col == 'B') {
                                // Numero de factura se valida en la columna C 
                                if ($value && strlen($value) > 0) {
	                                $transaction["invoiceNumberRow"] = $value;
									$invoiceNumberRow = $value;
                                    
                                } else {
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'El n&uacute;mero de factura es inv&aacute;lido');
                                    break;
                                }
                                
                            }
                            else if ($col == 'C') {        
                                $pointOfSale = $this->getDoctrine()->getRepository("AppBundle:PointOfSale")->findOneBy(array("pointOfSaleInnerId" => $value));
                                $invoice = $em->getRepository('AppBundle:Sale')->findOneBy(array('pointOfSale' => $pointOfSale,'invoiceNumber' => $invoiceNumberRow));
                                                                
                                if ($pointOfSale) {                                                                       
                                    
                                    if ($invoiceNumberRow && !$invoice) {
                                        
										$transaction["inner_id"] = $value;
                                        
                                    } else {
                                        $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                        ->createSaleLog($user, 'El numero de factura ingresado ya existe para el punto de venta asociado.');
                                        break;
                                    }
                                } else {
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'Ocurrio un error al obtener el valor del punto de venta, por favor comuniquese con soporte t&eacute;cnico.');
                                    break;
                                }
                            }
                           
                        
                            else if ($col == 'D') {
                                // valor de la factura                                
                                if (trim($value)) {
									$transaction["amount"] = $value;
                                } else {
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createSaleLog($user, 'El nombre del vendedor no es inv&aacute;lido.');
                                    break;
                                }
                                
                            }
                        }
                    }
                }     
                
	            if($transaction){ 
		                $pointOfSale = $this->getDoctrine()->getRepository("AppBundle:PointOfSale")->findOneBy(array("pointOfSaleInnerId" => $transaction["inner_id"]));
		                $staffPointOfSale = $this->getDoctrine()->getRepository("AppBundle:StaffPointOfSale")->findBy(array("pointOfSale" => $pointOfSale));
			            foreach($staffPointOfSale as $staff){
		 	              	$sale = new Sale();                
			                $sale->setCreatedBy($user->getId());
			                $sale->setParserRegister($parserRegister);  
			                $sale->setPointOfSale($pointOfSale);  
			                $sale->setPrice($transaction["amount"]);  
			                $sale->setIssuedAt($transaction["factDate"]);  
			                $sale->setInvoiceNumber($transaction["invoiceNumberRow"]);  
			                
			            
							
			            
			            }
			      
	                
                }else{
	                if($row >= 2){
		                $errors[$row] = $em->getRepository('AppBundle:LogParser')
	                                    ->createSaleLog($user, 'Error en la celda $row');
	                }                     
                }
                               
            }
        }
        
        
        if (sizeof($errors) > 0) {
            return $errors;
        }
        
        return $sales;
    }
    
    

    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * Validates the information of a editing sale
     *
     * @param Sale $sale sale to edit
     * @return string error message. NULL if no error
     */
    public function validateEdit($sale) {
        $em = $this->getDoctrine()->getManager();

        // Validate that price is a positive number
        if (! (is_numeric($sale->getPrice()) && (int) $sale->getPrice() >= 0)) {
            return "El precio debe ser un número mayor o igual a 0";
        }

        // Validate Client phone number
        if (! (
            is_numeric($sale->getClientPhone()) && 
            (strlen($sale->getClientPhone()) == 8 ||
                strlen($sale->getClientPhone()) == 13)
        )) {
            return "El número del cliente debe ser de 8 o 13 dígitos";
        }

        return null;
    }

    /**
     * Makes some changes to entity and updates it in db
     *
     * @param Sale $sale sale to update
     * @return Sale updated entity
     */
    public function edit($sale){
        $em = $this->getDoctrine()->getManager();

        // Set updated By
        $sale->setSkuFilterString($sale->getSku()->getSkuFilterString());

        // Store in DB
        $em->persist($sale);
        $em->flush();

        return $sale;
    }



    /**
     * @Route("/backend/reverse", name="backend_sale_reverse")
     */
    public function reverseAction(Request $request) {
        // Set in session to show as active
        $this->get("session")->set("module_id", 26);

        // Create form goal
        $reverse = new SaleReverse();
        $form = $this->createForm ( new SaleReverseType (), $reverse);
        $form->handleRequest ( $request );
        $em = $this->getDoctrine()->getManager();

        // Validar formulario
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {

                $reverse->setCreatedAt(new \DateTime());
                $reverse->setStatus("INGRESADO");
                $em->persist($reverse);
                $em->flush();
                $this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );



            }
            else {
                $this->addFlash ( 'error_message', $this->getParameter ( 'exito_reverse' ) );
            }
            //return $this->redirectToRoute ( "backend_sale_reverse" );
        }

        $skuCode = $request->get("skuCode", 0);
        $list = $em->getRepository ( 'AppBundle:Sale' )->getReverseList ($skuCode);
        $paginator = $this->get ( 'knp_paginator' );

        $pagination = $paginator->paginate (
            $list,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows" )
        );

        // Permission for actions
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/SaleReverse/index.html.twig',
            array(
                "form" => $form->createView (),
                "list" => $pagination,
                "permits" => $mp,
                "skuCode" => $skuCode
            )
        );
    }

    /**
     * @Route("/backend/reverse/apply/{id}", name="backend_sale_reverse_apply")
     */
    public function reverseEditAction(Request $request) {

    }

    /**
     * @Route("/backend/reverse/delete/{id}", name="backend_sale_reverse_delete")
     */
    public function reverseDeleteAction(Request $request) {
        $id = $request->get("id", 0);
        $em = $this->getDoctrine ()->getManager ();
        $reverse = $em->getRepository('AppBundle:SaleReverse')->findOneBySaleReverseId($id);
        if ($reverse) {
            // Try to delete
            try {

                $em->remove( $reverse );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }

        return $this->redirectToRoute ( "backend_sale_reverse" );
    }
	
}
