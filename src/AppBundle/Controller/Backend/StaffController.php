<?php
namespace AppBundle\Controller\Backend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Staff;
use AppBundle\Entity\Message;
use AppBundle\Entity\StaffPointOfSale;
use AppBundle\Entity\LogParser;
use AppBundle\Entity\ParserRegister;
use AppBundle\Form\PreInscriptionType;
use AppBundle\Form\StaffFileType;
use AppBundle\Form\StaffType;
use AppBundle\Form\MessageType;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Repository\EbClosion;
use AppBundle\Form\StaffPointOfSaleType;

class StaffController extends Controller
{

    private $moduleId = 6;

    /**
     *
     * @Route("/backend/staff", name="backend_staff")
     */
    public function indexAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);
		$this->moduleId = 6;
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $staff = new Staff();
        $form = $this->createForm(new PreInscriptionType(), $staff);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $error = $this->validateEdit($staff, NULL, NULL);

                if ($error) {
                    $this->addFlash('error_message', $error);
                } else {
                    $staff = $this->create($staff);
                    $this->addFlash('success_message',
                            $this->getParameter('exito_actualizar'));
                }
            }
        }

        // Use query parameters
        $citizenId = $request->get('dpi');
        $phone = $request->get('phone');
        $name = $request->get('name');

        $staffs = $em->getRepository('AppBundle:Staff')->search($name,
                $citizenId, $phone);
                
        $pagination = $paginator->paginate($staffs,
                $request->query->getInt('page', 1),
                $this->getParameter("number_of_rows"));

        // Form to send sms to a staff
        $smsForm = $this->createForm(new MessageType(), new Message());

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        return $this->render('@App/Backend/Staff/index.html.twig',
                array(
                        "staffs" => $pagination,
                        'form' => $form->createView(),
                        "permits" => $mp,
                        'smsForm' => $smsForm->createView()
                ));
    }

    /**
     *
     * @Route("/backend/report", name="backend_report")
     */
    public function reportAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $em = $this->getDoctrine()->getManager();

        $paginator = $this->get('knp_paginator');

        $send_csv = false;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $report = $_REQUEST["report"];
            $region = "";
            $where_clause = "";
            $start = "";
            if (isset($_REQUEST["start"]) and $_REQUEST["start"] != "") {
                $start .= $_REQUEST["start"];
            }
            $end = "";
            if (isset($_REQUEST["end"]) and $_REQUEST["end"] != "") {
                $end .= $_REQUEST["end"];
            }

            if (isset($_REQUEST["region"]) &&
                    implode("','", $_REQUEST["region"]) != "") {
                $where_clause .= " AND [CAMPO_REGION_ID] in ('" .
                        implode("','", $_REQUEST["region"]) . "') ";
            }
            $state = "";
            if (isset($_REQUEST["state"]) &&
                    implode("','", $_REQUEST["state"]) != "") {
                $where_clause .= " AND [CAMPO_STATE_ID] in ('" .
                        implode("','", $_REQUEST["state"]) . "') ";
            }
            $point_of_sale = "";
            if (isset($_REQUEST["point_of_sale"]) &&
                    implode("','", $_REQUEST["point_of_sale"]) != "") {
                $where_clause .= " AND [CAMPO_POINT_OF_SALE_ID] in('" .
                        implode("','", $_REQUEST["point_of_sale"]) . "') ";
            }
            $comercial_unit = "";
            if (isset($_REQUEST["comercial_unit"]) &&
                    implode("','", $_REQUEST["comercial_unit"]) != "") {
                if (count($_REQUEST["comercial_unit"]) > 0) {
                    $comercial_unit = implode(",", $_REQUEST["comercial_unit"]);
                    $where_clause .= " AND [CAMPO_SALE_ID] in (select sale_id from accrued_point_details where point_type_id in ($comercial_unit) )";
                }
            }
            $csv = "";
            switch ($report) {
                case "1":
                    $data[0] = $region;
                    $data[1] = $state;
                    $data[2] = $point_of_sale;
                    $data[3] = $comercial_unit;
                    $all_clients = array();
                    $total_max_quantity_register = 0;
                    $total_prom_units_per_user = 0;
                    $total_register_bills = 0;
                    $total_prom_units_per_bill = 0;
                    $total_units_buyed = 0;
                    $report_1 = $em->getRepository("AppBundle:Staff")->getReportKpisReport(
                            $where_clause, $start, $end);
                    $csv = ",Clientes activos, Facturas registradas, Promedio de facturas x cliente, Unidades compradas, Máximo unidades x factura, Promedio de unidades x factura,Promedio de unidades x cliente, Region\n";
                    foreach ($report_1 as $info) {
                        $info_array = array_unique(
                                explode(",", $info["client_activos"]));
                        $active_clients = count($info_array);
                        $register_bills = $info["facturas_registradas"];
                        $average_client_bills = number_format(
                                ($register_bills / $active_clients), 2);
                        $units_buyed = $info["unidades_compradas"];
                        $max_quantity_register = $info["maximo"];
                        $prom_units_per_bill = number_format(
                                ($units_buyed / $register_bills), 2);
                        $prom_units_per_user = number_format(
                                ($units_buyed / $active_clients), 2);
                        $total_register_bills = $register_bills +
                                $total_register_bills;
                        $total_units_buyed = $total_units_buyed + $units_buyed;
                        if ($total_max_quantity_register < $max_quantity_register) {
                            $total_max_quantity_register = $max_quantity_register;
                        }

                        $all_clients = array_unique(
                                array_merge($info_array, $all_clients));
                        $csv .= ",$active_clients,$register_bills,$average_client_bills,$units_buyed,$max_quantity_register,$prom_units_per_bill,$prom_units_per_user,$info[region]\n";
                    }
                    if (count($all_clients) > 0) {
                        $total_active_clients = count($all_clients);
                        $total_average_client_bills = number_format(
                                ($total_register_bills / $total_active_clients),
                                2);
                        $total_prom_units_per_bill = number_format(
                                ($total_units_buyed / $total_register_bills), 2);
                        $total_prom_units_per_user = number_format(
                                ($total_units_buyed / $total_active_clients), 2);
                        $csv .= "TOTALES, $total_active_clients,$total_register_bills,$total_average_client_bills,$total_units_buyed,$total_max_quantity_register,$total_prom_units_per_bill,$total_prom_units_per_user,";
                        $send_csv = true;
                    }
                    break;
                case "2":
                    $report_2 = $em->getRepository("AppBundle:Staff")->getRedeemExchangePoints(
                            $where_clause, $start, $end);
                    $csv = "Número de canje,Fecha de canje,Hora de canje,Código del cliente,Nombre del cliente,DPI,NIT,Celular,Correo electrónico,Premio,Valor del premio,Quetzales redimidos de Cemento UGC,Quetzales redimidos de Mezclas Listas,Quetzales redimidos de Concreto,Quetzales redimidos de Cupones,Total de Quetzales Redimidos\n";

                    foreach ($report_2 as $info) {
                        $total_redimido = $info["Quetzales_redimidos_de_mezclas_ugc"] +
                                $info["Quetzales_redimidos_de_mezclas_listas"] +
                                $info["Quetzales_redimidos_de_mixto_listas"] +
                                $info["Quetzales_redimidos_de_cupones"];
                        $csv .= "$info[prize_exchange_id],$info[date],$info[hour],$info[staff_id],$info[name],$info[citizen_id],$info[tax_identifier],$info[phone_main],$info[email],$info[prize],$info[amount],$info[Quetzales_redimidos_de_mezclas_ugc],$info[Quetzales_redimidos_de_mezclas_listas],$info[Quetzales_redimidos_de_mixto_listas],$info[Quetzales_redimidos_de_cupones],$total_redimido\n";
                        $send_csv = true;
                    }
                    break;
                case "3":
                    $report_3 = $em->getRepository("AppBundle:Staff")->getReportKpisRedemption(
                            $start, $end);
                    $csv = "Total de canjes,Clientes con canjes,Promedio de canjes x cliente,Quetzales redimidos de Cemento UGC,Quetzales redimidos de Mezclas Listas,Quetzales redimidos de Concreto ML,Quetzales cupones,Quetzales redimidos,Promedio de Quetzales x cliente,Promedio de Quetzales x canje\n";
                    foreach ($report_3 as $info) {
                        if ($info["all_staff_id"] > 0) {
                            $total_clients_arr = explode(",",
                                    $info["all_staff_id"]);
                            $total_clients = count($total_clients_arr);
                            $prom_client_redeems = $info["cont"] / $total_clients;
                            $prom_client_quetzales = $info["quetzales_redimidos"] /
                                    $total_clients;
                            $prom_redeem_quetzales = $info["quetzales_redimidos"] /
                                    $info["cont"];
                            $csv .= "$info[cont],$total_clients,$prom_client_redeems,$info[quetzales_ugc],$info[quetzales_mezclas_listas],$info[quetzales_mixto_listo],$info[quetzales_cupon],$info[quetzales_redimidos],$prom_client_quetzales,$prom_redeem_quetzales\n";
                            $send_csv = true;
                        }
                    }

                    break;

                case "4":
                    $report_3 = $em->getRepository("AppBundle:Staff")->getNewClients(
                            $where_clause, $start, $end);
                    $csv = "\n";
                    foreach ($report_3 as $info) {

                        $csv .= "\n";
                        $send_csv = true;
                    }

                    break;
            }

            if ($send_csv) {
                $data = array(
                        "data" => $csv
                );
                $response = $this->render('@App/Backend/Staff/report.csv.twig',
                        array(
                                'data' => $data
                        ));
                $filename = "export_" . date("Y_m_d_His") . ".csv";
                $response->headers->set('Content-Type', 'text/csv');
                $response->headers->set('Content-Disposition',
                        'attachment; filename=' . $filename);
                return $response;
            } else {
                $this->addFlash('error_message',
                        "No fueron encontrados registros con estos datos, por favor intenta nuevamente con otros filtros de busqueda.");
            }
        }

        $region = $em->getRepository("AppBundle:Region")->findAll();
        $state = $em->getRepository("AppBundle:State")->findAll();
        $state = $em->getRepository("AppBundle:State")->findAll();
        $PointOfSale = $em->getRepository("AppBundle:PointOfSale")->findAll();

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        return $this->render('@App/Backend/Staff/report.html.twig',
                array(
                        "region" => $region,
                        "state" => $state,
                        "PointOfSale" => $PointOfSale,
                        "permits" => $mp
                ));
    }

    /**
     *
     * @Route("/backend/staff/redeem/{staffId}", name="backend_staff_redeem_points", requirements={"staffId": "\d+"})
     */
    public function redeemAction (Request $request, Staff $staff)
    {
        $staffId = $request->get("staffId", false);
        if (! $staffId) {
            return $this->redirectToRoute("backend_staff");
        }

        $userData = array(
                "staff_id" => $staffId
        );
        $this->get("session")->set("userData", $userData);

        $em = $this->getDoctrine()->getManager();

        $staff = $em->getRepository("AppBundle:Staff")->findOneBy(
                array(
                        "staffId" => $staffId
                ));

        $staffs = $em->getRepository('AppBundle:Prize')->getList();
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate($staffs,
                $request->query->getInt('page', 1),
                $this->getParameter("number_of_rows"));
        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));

        return $this->render('@App/Frontend/Staff/prize.html.twig',
                array(
                        "staffs" => $pagination,
                        'form' => '',
                        "permits" => $mp,
                        "staff" => $staff
                ));
    }

    /**
     *
     * @Route("/backend/staff/prizeDetails", name="backend_prize_details", requirements={"id": "\d+"})
     */
    public function prizeDetailsAction (Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $staffId = $request->get("staffId", false);
        if (! $staffId) {
            return $this->redirectToRoute("backend_staff");
        }
        $staff = $em->getRepository("AppBundle:Staff")->findOneBy(
                array(
                        "staffId" => $staffId
                ));

        $prize_id = $request->get("id", "");
        $staffs = $em->getRepository('AppBundle:Prize')->findOneById($prize_id);
        $prizeSteps = $em->getRepository('AppBundle:PrizeStep')->findBy(
                array(
                        'prize' => $prize_id
                ));

        return $this->render('@App/Frontend/Staff/prizeDetail.html.twig',
                array(
                        "staffs" => $staffs,
                        "prizeSteps" => $prizeSteps,
                        "staff" => $staff
                ));
    }

    /**
     *
     * @Route("/backend/staff/upload", name="backend_staff_upload")
     */
    public function uploadAction (Request $request)
    {
        // Form to upload file
        $staffFile = new Staff();
        $form = $this->createForm(new StaffFileType(), $staffFile);

        $em = $this->getDoctrine()->getManager();

        // Process to save file
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $error = true;
            if ($form->isValid()) {
                $file = $staffFile->getFile();

                // Move file
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()) . '.' .
                        $file->getClientOriginalExtension();

                // Move the file to the directory where excels are stored
                $fileDir = $this->getParameter('staff_upload_folder');
                $file->move($fileDir, $fileName);

                // Check that file was uploaded
                $filePath = $fileDir . '/' . $fileName;

                if (file_exists($filePath)) {
                    try {
                        $staffs = $this->readExcelFile($filePath, $fileName);

                        $error = true;
                        $errors = array();

                        // Keep type of elements to persist
                        $type = null;
                        $logParserType = 'AppBundle\Entity\LogParser';
                        $staffType = 'AppBundle\Entity\Staff';

                        // Array to check repeated entities
                        $staffArray = array();
                        $staffPointOfSaleArray = array();

                        foreach ($staffs as $key => $staff) {
                            // Save sale
                            if (get_class($staff) == $logParserType) {
                                array_push($errors,
                                        "Fila No. $key, " . $staff->getLogText());

                                // Save error to db
                                $em->persist($staff);
                            } else {
                                // Look in staffs already added if this one
                                // already exists
                                $sRepeated = $this->findStaffInStaffs(
                                        $staffArray, $staff);
                                if ($sRepeated == NULL) {
                                    // Not found in array of staffs
                                    array_push($staffArray, $staff);

                                    // Set point of sale
                                    $pointOfSale = $staff->getPointOfSale();
                                    $theStaff = $staff;

                                    $em->persist($theStaff);
                                } else {
                                    $pointOfSale = $staff->getPointOfSale();
                                    $theStaff = $sRepeated;
                                }

                                // Look if this staff is already assigned to the
                                // point of sale
                                $sposRepeated = $this->findInStaffPointOfSales(
                                        $staffPointOfSaleArray, $theStaff,
                                        $pointOfSale);

                                if ($sposRepeated == NULL) {
                                    // Not found in array
                                    // Need to create entity StaffPointOfSale
                                    $staffPointOfSale = new StaffPointOfSale();
                                    $staffPointOfSale->setStaff($theStaff);
                                    $staffPointOfSale->setPointOfSale(
                                            $pointOfSale);
                                    $staffPointOfSale->setCreatedBy(
                                            $this->getUser()
                                                ->getId());
                                    $staffPointOfSale->setCreatedAt(
                                            new \DateTime());

                                    array_push($staffPointOfSaleArray,
                                            $staffPointOfSale);

                                    $em->persist($staffPointOfSale);
                                }
                            }
                        }

                        if (sizeof($errors) == 0) {
                            $error = false;
                        }
                        $em->flush();
                    } catch (Exception $e) {}
                }
            }

            if ($error) {
                $errorsMess = join("<br>", $errors);
                $errorsMess = $this->container->getParameter(
                        'error_sale_upload') . "<div class='list-error'> " .
                        $errorsMess . "</div>";

                array_unshift($errors,
                        $this->container->getParameter('error_sale_upload'));

                // Error message
                $this->addFlash('error_message', $errorsMess);
            } else {
                // Success message
                $this->addFlash('success_message',
                        $this->container->getParameter('exito'));
            }
        }

        return $this->render('@App/Backend/Staff/upload.html.twig',
                array(
                        'form' => $form->createView()
                ));
    }

    /**
     *
     * @Route("/backend/staff/edit/{id}", name="backend_staff_edit", requirements={"id": "\d+"})
     */
    public function editAction (Request $request, Staff $staff)
    {
        if ($staff) {
            $em = $this->getDoctrine()->getManager();

            // Check that it's not a deleted staff
            $staffStatus = $staff->getStaffStatus();
            if ($staffStatus && $staffStatus->getId() == 3) {
                // Deleted staff, can't edit
                throw $this->createNotFoundException('No existe');
            }

            // Add points of sale
            $staffPointsOfSale = $em->getRepository(
                    'AppBundle:StaffPointOfSale')->findBy(
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

            $options["showPointOfSale"] = true;
            $options["showAllJobPositions"] = true;
            $form = $this->createForm(new PreInscriptionType(), $staff, $options);

            // Old info
            $oldTaxIdentifier = $staff->getTaxIdentifier();
            $oldCitizenId = $staff->getCitizenId();

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $error = $this->validateEdit($staff, $oldTaxIdentifier,
                            $oldCitizenId);

                    if ($error) {
                        $this->addFlash('error_message', $error);
                    } else {
                        $user = $this->edit($staff, $cloneSpos);
                        $em->flush();
                        $this->addFlash('success_message',
                                $this->getParameter('exito_actualizar'));
                    }
                } else {
                    // Error validation
                    $this->addFlash('error_message',
                            $this->getParameter('error_form'));
                }
            }
            return $this->render('@App/Backend/Staff/edit.html.twig',
                    array(
                            "form" => $form->createView()
                    ));
        } else {
            $this->addFlash('error_message', $this->getParameter('error_editar'));
        }
        return $this->redirectToRoute("backend_staff");
    }

    /**
     *
     * @Route("/backend/staff/delete/{id}", name="backend_staff_delete", requirements={"id": "\d+"})
     */
    public function deleteAction (Request $request, Staff $staff)
    {
        // Try to delete
        try {
            $em = $this->getDoctrine()->getManager();

            // Change status to eliminated
            $staff->setStaffStatus(
                    $em->getReference('AppBundle\Entity\StaffStatus', 3));
            $em->flush();

            $this->addFlash('success_message',
                    $this->getParameter('exito_eliminar'));
        } catch (\Exception $e) {
            $this->addFlash('error_message',
                    $this->getParameter('error_eliminar'));
        }

        return $this->redirectToRoute("backend_staff");
    }

    /**
     *
     * @Route("/backend/staff/view/{id}", name="backend_staff_view", requirements={"id": "\d+"})
     */
    public function viewAction (Request $request, Staff $staff)
    {
        ini_set('memory_limit', '-1');

        $em = $this->getDoctrine()->getManager();

        // Get points information
        $exchangedPoints = $em->getRepository('AppBundle:PrizeExchange')->getAllExchangeCredits(
                $staff->getStaffId());
        $exchangedPoints = $exchangedPoints[0]['credits'];

        $accumulatedPoints = $em->getRepository('AppBundle:SaleStaff')->getStaffPoints(
                $staff->getStaffId());
        $accumulatedPoints = $accumulatedPoints[0]['points'];
        // facturas registradas
        $totalInvoice = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "staff" => $staff->getStaffId()
                ));

        // facturas aprobadas
        $totalInvoiceAppoved = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "staff" => $staff->getStaffId(),
                        "invoiceStatus" => 'ACEPTADO'
                ));
        $totalInvoicePending = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "staff" => $staff->getStaffId(),
                        "invoiceStatus" => 'PENDIENTE'
                ));
        $totalInvoiceRejected = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "staff" => $staff->getStaffId(),
                        "invoiceStatus" => 'RECHAZADO'
                ));

        

        // Check for nulls
        if ($accumulatedPoints == NULL) {
            $accumulatedPoints = 0;
        }

        if ($exchangedPoints == NULL) {
            $exchangedPoints = 0;
        }

        $availablePoints = $accumulatedPoints - $exchangedPoints;

        // Point of sale information
        $staffPointsOfSale = $em->getRepository('AppBundle:StaffPointOfSale')->findOneBy(
                array(
                        'staff' => $staff,
                        "status" => "ACTIVE"
                ));        

        // puntos promocionales
        $pointDetail = $em->getRepository("AppBundle:Staff")->getPromotionalPoints(
                array(
                        $staff->getStaffId()
                ), 1, 12);

        $pointDetail = (count($pointDetail) > 0) ? $pointDetail[0]["promotional"] : 0;

        return $this->render('@App/Backend/Staff/view.html.twig',
                array(
                        "staff" => $staff,
                        'exchangedPoints' => $exchangedPoints,
                        'accumulatedPoints' => $accumulatedPoints,
                        'availablePoints' => $availablePoints,
                        'pos' => $staffPointsOfSale,
                        "totalInvoice" => count($totalInvoice),
                        "totalInvoiceAppoved" => count($totalInvoiceAppoved),
                        "totalInvoicePending" => count($totalInvoicePending),                       
                        "totalInvoiceRejected" => count($totalInvoiceRejected),
                        "totalInvoiceList" => $totalInvoice,
                        "totalPuntosPromo" => $pointDetail,
                        "totalAcumuladoyPromo" => $accumulatedPoints +
                        $pointDetail
                ));
    }

    /**
     *
     * @Route("/backend/staff/pos/{staffId}", name="backend_staff_point_of_sale", requirements={"staffId": "\d+"})
     */
    public function staffPosAction (Request $request, Staff $staff)
    {
        if ($staff) {

            $this->get("session")->set("module_id", $this->moduleId);
            
            $em = $this->getDoctrine()->getManager();
            
            $list = $em->getRepository('AppBundle:StaffPointOfSale')->findBy(
                    array(
                            "staff" => $staff,
                            "status" => "ACTIVE"
                    ));           

            // quitar los puntos de venta previamente agregados del select
            $arrayPos = array();
            $stringPos = "";           
            foreach ($list as $item) {
                array_push($arrayPos, $item->getPointOfSale()->getPointOfSaleId());                            
            }
            $stringPos = implode(",", $arrayPos);
                                   
            
            $options = array("staffPointsOfSale" => $stringPos);
            $spos = new StaffPointOfSale();
            $form = $this->createForm(new StaffPointOfSaleType(), $spos, $options);

            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    
                    // limitar a un solo punto de venta los usuarios de tipo bar tender
                    if ($staff->getJobPosition()->getId() == 3 && count($list) > 0) {
                        $this->addFlash('error_message',
                                $this->getParameter('pos_error_msg'));
                    } else {

                        $spos->setCreatedAt(new \DateTime());
                        $spos->setCreatedBy($this->getUser()
                            ->getId());
                        $spos->setStaff($staff);
                        $spos->setStatus("ACTIVE");
    
                        $em->persist($spos);
                        $em->flush();
    
                        $this->addFlash('success_message',
                            $this->getParameter('exito'));
                    }
                } else {
                    // Error validation
                    $this->addFlash('error_message',
                            $this->getParameter('error_form'));
                }

                return $this->redirectToRoute("backend_staff_point_of_sale",
                        array(
                                "staffId" => $staff->getStaffId()
                        ));
            }

            

            $mp = EbClosion::getModulePermission($this->moduleId,
                    $this->get("session")->get("userModules"));

            return $this->render('@App/Backend/Staff/Pos/index.html.twig',
                    array(
                            "list" => $list,
                            "form" => $form->createView(),
                            "permits" => $mp,
                            "staff" => $staff
                    ));
        } else {
            $this->addFlash('error_message', $this->getParameter('error_form'));
            return $this->redirectToRoute("backend_staff");
        }
    }

    /**
     *
     * @Route("/backend/staff/pos/{spos}/delete", name="backend_staff_point_of_sale_delete", requirements={"spos": "\d+"})
     */
    public function staffPosDeleteAction (Request $request,
            StaffPointOfSale $spos)
    {
        if ($spos) {
            $spos->setStatus("DELETED");
            $spos->setUpdatedBy($this->getUser()
                ->getId());
            $spos->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($spos);
            $em->flush();

            $this->addFlash('success_message',
                    $this->getParameter('exito_eliminar'));

            return $this->redirectToRoute("backend_staff_point_of_sale",
                    array(
                            "staffId" => $spos->getStaff()
                                ->getStaffId()
                    ));
        }
    }

    public function readExcelFile ($path, $fileName)
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($path);

        // Needed
        $em = $this->getDoctrine()->getManager();
        $staffs = array();
        $errors = array();

        // Error stuff
        $errorType = $em->getRepository('AppBundle:ParserType')->findOneByParserTypeId(
                1);
        $user = $this->getUser();

        // Create Parser Register per file
        $parserRegister = new ParserRegister();
        $parserRegister->setFileName($fileName);
        $parserRegister->setCreatedAt(new \DateTime());
        $parserRegister->setCreatedBy($user);
        $parserRegister->setParserType(
                $em->getReference('AppBundle\Entity\ParserType', 3));
        $em->persist($parserRegister);

        foreach ($phpExcelObject->getWorksheetIterator() as $worksheet) {
            // Every worksheet
            foreach ($worksheet->getRowIterator() as $row) {
                // Every row of this worksheet
                // Each row has a staff
                $staff = new Staff();
                $staff->setCreatedBy($user->getId());
                $staff->setCreatedAt(new \DateTime());
                $staff->setParserRegister($parserRegister);
                $staff->setStaffStatus(
                        $em->getReference('AppBundle\Entity\StaffStatus', 2));

                $cellIterator = $row->getCellIterator();

                // Loop all cells, even if it is not set
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach ($cellIterator as $cell) {
                    if (! is_null($cell)) {
                        // Every valid cell of this row

                        // Name of column
                        $col = $cell->getColumn();

                        // Row number
                        $row = $cell->getRow();

                        // Value
                        $value = $cell->getValue();

                        if ($row >= 2) {
                            if ($col == 'A') {
                                // Point of sale
                                // Look for the point of sale
                                $pointOfSale = $em->getRepository(
                                        'AppBundle:PointOfSale')->findOneByPointOfSaleInnerId(
                                        $value);

                                if ($pointOfSale) {
                                    $staff->setPointOfSale($pointOfSale);
                                } else {
                                    // No Point of sale
                                    // Create error
                                    $errors[$row] = $em->getRepository(
                                            'AppBundle:LogParser')->createStaffLog(
                                            $user,
                                            'No se encontró punto de venta');
                                    break;
                                }
                            } else 
                                if ($col == 'B') {
                                    // Name
                                    $staff->setName($value);
                                } else 
                                    if ($col == 'C') {
                                        // Tax identifier, has to be unique
                                        $duplicateTax = $em->getRepository(
                                                'AppBundle:Staff')->findOneByTaxIdentifier(
                                                $value);

                                        if ($duplicateTax) {
                                            // It already exists, error
                                            $errors[$row] = $em->getRepository(
                                                    'AppBundle:LogParser')->createStaffLog(
                                                    $user, 'Ya existe ese nit');
                                            break;
                                        } else {
                                            $staff->setTaxIdentifier($value);
                                        }
                                    } else 
                                        if ($col == 'D') {
                                            // Citizend id, has to be unique
                                            $duplicateCit = $em->getRepository(
                                                    'AppBundle:Staff')->findOneByCitizenId(
                                                    $value);

                                            if ($duplicateCit) {
                                                // It already exists, error
                                                $errors[$row] = $em->getRepository(
                                                        'AppBundle:LogParser')->createStaffLog(
                                                        $user,
                                                        'Ya existe ese identificador');
                                                break;
                                            }

                                            if (strlen($value) != 13 ||
                                                    ! is_numeric($value)) {
                                                // Citizen Id must be a 13 digit
                                                $errors[$row] = $em->getRepository(
                                                        'AppBundle:LogParser')->createStaffLog(
                                                        $user,
                                                        'El DPI debe ser de 13 dígitos');
                                                break;
                                            } else {
                                                $staff->setCitizenId($value);

                                                // Encode Citizen Id as password
                                                $userHelper = $this->get(
                                                        'user.helper');
                                                $staff->setPasswd(
                                                        $userHelper->encodePassword(
                                                                $staff, $value));
                                            }
                                        } else 
                                            if ($col == 'E') {
                                                // Gender
                                                $staff->setGender($value);
                                            } else 
                                                if ($col == 'F') {
                                                    // Birth date
                                                    try {
                                                        if (\PHPExcel_Shared_Date::isDateTime(
                                                                $cell)) {
                                                            $birthDate = new \DateTime(
                                                                    "@" .
                                                                    \PHPExcel_Shared_Date::ExcelToPHP(
                                                                            $value));
                                                        } else {
                                                            $birthDate = new \DateTime(
                                                                    $value);
                                                        }

                                                        // Can't be after now
                                                        if ($birthDate >
                                                                new \DateTime()) {
                                                            // Wrong date
                                                            $errors[$row] = $em->getRepository(
                                                                    'AppBundle:LogParser')->createStaffLog(
                                                                    $user,
                                                                    'La fecha no puede ser futuro');
                                                            break;
                                                        }

                                                        $staff->setBirthdate(
                                                                $birthDate);
                                                    } catch (\Exception $e) {
                                                        // Wrong date format
                                                        $errors[$row] = $em->getRepository(
                                                                'AppBundle:LogParser')->createStaffLog(
                                                                $user,
                                                                'Fecha con mal formato');
                                                        break;
                                                    }
                                                } else 
                                                    if ($col == 'G') {
                                                        // Secondary phone
                                                        $staff->setPhoneSecondary(
                                                                $value);
                                                    } else 
                                                        if ($col == 'H') {
                                                            // Main phone
                                                            $staff->setPhoneMain(
                                                                    $value);
                                                        } else 
                                                            if ($col == 'I') {
                                                                // Email
                                                                if ($value != 0 &&
                                                                        ! filter_var(
                                                                                $value,
                                                                                FILTER_VALIDATE_EMAIL)) {
                                                                    // Invalid
                                                                    // email
                                                                    $errors[$row] = $em->getRepository(
                                                                            'AppBundle:LogParser')->createStaffLog(
                                                                            $user,
                                                                            'Correo con mal formato');
                                                                    break;
                                                                }

                                                                $staff->setEmail(
                                                                        $value);
                                                            } else 
                                                                if ($col == 'J') {
                                                                    // State
                                                                    $state = $em->getRepository(
                                                                            'AppBundle:State')->findOneByName(
                                                                            $value);

                                                                    if ($state) {
                                                                        $staff->setState(
                                                                                $state);
                                                                    } else {
                                                                        // No
                                                                        // state
                                                                        // Create
                                                                        // error
                                                                        $errors[$row] = $em->getRepository(
                                                                                'AppBundle:LogParser')->createStaffLog(
                                                                                $user,
                                                                                'No se encontró el departamento');
                                                                        break;
                                                                    }
                                                                } else 
                                                                    if ($col ==
                                                                            'K') {
                                                                        // City
                                                                        $city = $em->getRepository(
                                                                                'AppBundle:City')->findOneByName(
                                                                                $value);

                                                                        if ($city) {
                                                                            $staff->setCity(
                                                                                    $city);
                                                                        } else {
                                                                            // No
                                                                            // state
                                                                            // Create
                                                                            // error
                                                                            $errors[$row] = $em->getRepository(
                                                                                    'AppBundle:LogParser')->createStaffLog(
                                                                                    $user,
                                                                                    'No se encontró el municipio');
                                                                            break;
                                                                        }
                                                                    } else 
                                                                        if ($col ==
                                                                                'L') {
                                                                            // Country
                                                                            $country = $em->getRepository(
                                                                                    'AppBundle:Country')->findOneByName(
                                                                                    $value);

                                                                            if ($country) {
                                                                                $staff->setCountry(
                                                                                        $country);
                                                                            } else {
                                                                                // No
                                                                                // state
                                                                                // Create
                                                                                // error
                                                                                $errors[$row] = $em->getRepository(
                                                                                        'AppBundle:LogParser')->createStaffLog(
                                                                                        $user,
                                                                                        'No se encontró el país');
                                                                                break;
                                                                            }
                                                                        } else 
                                                                            if ($col ==
                                                                                    'M') {
                                                                                // Address1
                                                                                $staff->setAddress1(
                                                                                        $value);
                                                                            } else 
                                                                                if ($col ==
                                                                                        'N') {
                                                                                    // Address2
                                                                                    $staff->setAddress2(
                                                                                            $value);
                                                                                } else 
                                                                                    if ($col ==
                                                                                            'O') {
                                                                                        // JobPosition
                                                                                        $jobPosition = $em->getRepository(
                                                                                                'AppBundle:JobPosition')->findOneByName(
                                                                                                $value);

                                                                                        if ($jobPosition) {
                                                                                            $staff->setJobPosition(
                                                                                                    $jobPosition);
                                                                                        } else {
                                                                                            // No
                                                                                            // state
                                                                                            // Create
                                                                                            // error
                                                                                            $errors[$row] = $em->getRepository(
                                                                                                    'AppBundle:LogParser')->createStaffLog(
                                                                                                    $user,
                                                                                                    'No se encontró el puesto');
                                                                                            break;
                                                                                        }
                                                                                    }
                        }
                    }
                }

                // After each row add to point of sale to point of sales
                if ($staff->isValid()) {
                    array_push($staffs, $staff);
                }
            }
        }

        if (sizeof($errors) > 0) {
            return $errors;
        }

        return $staffs;
    }

    /**
     * Validates the information of a editing point of sale
     *
     * @param Staff $staff
     *            staff to edit
     * @param string $oldTaxIdentifier
     *            the tax identifier before edit
     * @param string $oldCitizenId
     *            the staff citizen id
     * @return string error message. NULL if no error
     */
    public function validateEdit ($staff, $oldTaxIdentifier, $oldCitizenId)
    {
        $em = $this->getDoctrine()->getManager();

        // Tax identifier has to be unique
        if ($staff->getTaxIdentifier() != $oldTaxIdentifier) {
            $duplicate = $em->getRepository('AppBundle:Staff')->findOneByTaxIdentifier(
                    $staff->getTaxIdentifier());

            if ($duplicate) {
                return "Nit ya existe en base de datos";
            }
        }

        // Point of sale inner id has to be unique
        if ($staff->getCitizenId() != $oldCitizenId) {
            $duplicate = $em->getRepository('AppBundle:Staff')->findOneByCitizenId(
                    $staff->getCitizenId());

            if ($duplicate) {
                return "El DPI ya existe";
            }
        }

        // Citizen id has to be 13 digit
        if (strlen($staff->getCitizenId()) != 13 ||
                ! is_numeric($staff->getCitizenId())) {
            return "El DPI deben ser 13 dígitos";
        }

        // Phones have to be 8 digit
        if (strlen($staff->getPhoneMain()) != 8 ||
                ! is_numeric($staff->getPhoneMain())) {
            return "El número de teléfono principal debe ser 8 dígitos";
        }

        /*
         * if ($staff->getPhoneSecondary()) {
         * if (
         * strlen($staff->getPhoneSecondary()) != 8 ||
         * !is_numeric($staff->getPhoneSecondary())
         * ) {
         * return "El número de teléfono secundario debe ser 8 dígitos";
         * }
         * }
         */

        // Check that staff status is valid
        $staffStatus = $staff->getStaffStatus();
        if ($staffStatus == NULL || $staffStatus->getId() == 3) {
            // Invalid edit or create status
            return "Estado inválido";
        }

        // Email has to be a regex email
        /*
         * if ($staff->getEmail() != '0' && !filter_var($staff->getEmail(),
         * FILTER_VALIDATE_EMAIL)) {
         * return 'Su correo es inválido';
         * }
         */

        return null;
    }

    /**
     * Makes some changes to entity and updates it in db
     *
     * @param Staff $staff
     *            staff to update
     * @param array $originalSpos
     *            array of the original StaffPointOfSale
     * @return Staff updated entity
     */
    public function edit ($staff, $originalSpos)
    {
        $em = $this->getDoctrine()->getManager();

        // Encript password
        $userHelper = $this->get('user.helper');
        $staff->setPasswd(
                $userHelper->encodePassword($staff, $staff->getCitizenId()));

        // Separate StaffPointsOfSale in 3 sets, create, delete, keep
        /*
         * $sets3 = $this->separateStaffPointsOfSale(
         * $staff->getStaffPointsOfSale(),
         * $originalSpos
         * );
         *
         * $create = $sets3['new'];
         * $delete = $sets3['old'];
         *
         * // Create StaffPointOfSale
         * foreach ($create as $staffPointOfSale) {
         * $new = new StaffPointOfSale();
         * $new->setPointOfSale($staffPointOfSale->getPointOfSale());
         * $new->setCreatedAt(new \DateTime());
         * $new->setCreatedBy($this->getUser()->getId());
         * $new->setStatus('ACTIVE');
         * $new->setStaff($staff);
         *
         * $em->persist($new);
         * }
         *
         * // Set as moved StaffPointOfSale
         * foreach ($delete as $staffPointOfSale) {
         * // Look for the id and staff and set as moved
         * $move = $em->getRepository('AppBundle:StaffPointOfSale')
         * ->findOneBy(array(
         * 'staff' => $staff,
         * 'status' => 'ACTIVE',
         * 'pointOfSale' => $staffPointOfSale->getPointOfSale()
         * ));
         *
         * // Set original PointOfSale
         * $move->setPointOfSale($staffPointOfSale->getPointOfSale());
         *
         * $move->setMoved();
         * $em->persist($move);
         * }
         */

        // Store in DB
        $em->persist($staff);
        $em->flush();

        return $staff;
    }

    /**
     * Inserts entity in database
     *
     * @param Staff $staff
     *            staff to create
     * @return Staff inserted entity
     */
    public function create ($staff)
    {
        $em = $this->getDoctrine()->getManager();

        $staff->setCreatedAt(new \DateTime());
        $staff->setCreatedBy($this->getUser()
            ->getId());

        if (! $staff->getStaffStatus()) {
            // Start as active staff
            $staff->setStaffStatus(
                    $em->getReference('AppBundle\Entity\StaffStatus', 2));
        }

        // Encript password
        $userHelper = $this->get('user.helper');
        $staff->setPasswd(
                $userHelper->encodePassword($staff, $staff->getCitizenId()));

        // Create StaffPointOfSale if necessary
        $staffPointsOfSale = $staff->getSTaffPointsOfSale();
        foreach ($staffPointsOfSale as $staffPointOfSale) {
            $staffPointOfSale->setCreatedAt(new \DateTime());
            $staffPointOfSale->setCreatedBy($this->getUser()
                ->getId());
            $staffPointOfSale->setStaff($staff);

            $em->persist($staffPointOfSale);
        }

        // Store in DB
        $em->persist($staff);
        $em->flush();

        return $staff;
    }

    /**
     * Looks a staff in an array of staffs by citizen id
     *
     * @param array $haystac
     *            haystack of staffs
     * @param Staff $needle
     *            needle of staff
     * @return Staff Found staff. Null if not found
     */
    public function findStaffInStaffs ($haystack, $needle)
    {
        foreach ($haystack as $staff) {
            if ($staff->getCitizenId() == $needle->getCitizenId()) {
                return $staff;
            }
        }

        // Default value
        return NULL;
    }

    /**
     * Looks a staff, by citizen id; point of sale, by inner id; in an array of
     * staffPointOfSale
     *
     * @param array $haystack
     *            array of StaffPointOfSale
     * @param Staff $staff
     *            staff to look in array
     * @param PointOfSale $pointOfSale
     *            pointOfSale to look in array
     * @return StaffPointOfSale found element. NULL if not found
     */
    public function findInStaffPointOfSales ($haystack, $staff, $pointOfSale)
    {
        foreach ($haystack as $staffPointOfSale) {
            $sposStaff = $staffPointOfSale->getStaff();
            $sposPointOfSale = $staffPointOfSale->getPointOfSale();

            if ($sposStaff->getCitizenId() == $staff->getCitizenId() &&
                    $sposPointOfSale->getPointOfSaleInnerId() ==
                    $pointOfSale->getPointOfSaleInnerId()) {
                return $staffPointOfSale;
            }
        }

        // Default value
        return NULL;
    }

    /**
     * Gets 3 arrays from 2 sets of StaffPointOfSale.
     * The intersection, and the 2 sets minus the intersection
     * It assumes it doesn't have
     *
     * @param array $news
     *            one of the two sets of StaffPointOfSale
     * @param array $olds
     *            second set of the two sets of StaffPointOfSale
     */
    private function separateStaffPointsOfSale ($news, $olds)
    {
        // We have 3 arrays that get the needed information
        $intersection = array();
        $newSet = array();

        foreach ($news as $new) {
            // Look for the staffPointOfSale in the old staffPointsOfSale
            $index = $this->indexOfSpos($olds, $new);

            if ($index !== NULL) {
                // It was found, part of intersection
                array_push($intersection, $new);

                // Remove found one from the olds
                unset($olds[$index]);
            } else {
                // It wasn't found part of the newSet
                array_push($newSet, $new);
            }
        }

        // exit;
        return array(
                'new' => $newSet,
                'intersection' => $intersection,
                'old' => $olds
        );
    }

    /**
     * Looks for the index of a StaffPointOfSale in an array of
     * StaffPointsOfSale
     * It compares PointOfSale
     *
     * @param array $haystack
     *            array o StaffPointOfSale
     * @param StaffPointOfSale $needle
     *            needle to look for
     * @return any index of element in array. -1 if not found
     */
    private function indexOfSpos ($haystack, $needle)
    {
        foreach ($haystack as $key => $staffPointOfSale) {
            // echo $staffPointOfSale->getPointOfSale()->getPointOfSaleId();
            // echo ' - ';
            // echo $needle->getPointOfSale()->getPointOfSaleId();
            // echo '<br>';
            if ($staffPointOfSale->getPointOfSale()->getPointOfSaleId() ==
                    $needle->getPointOfSale()->getPointOfSaleId()) {
                return $key;
            }
        }

        return null;
    }
}
