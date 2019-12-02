<?php
namespace AppBundle\Controller\Backend;
use AppBundle\Entity\Sale;
use AppBundle\Entity\SaleStaff;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\RegisterPending;
use AppBundle\Entity\Staff;
use AppBundle\Entity\PromoCupon;
use AppBundle\Entity\AccruedPointDetails;
use AppBundle\Entity\StaffPointOfSale;
use AppBundle\Form\RegisterPendingType;
use AppBundle\Helper\SessionHelper;
use AppBundle\Repository\EbClosion;
use AppBundle\Entity\StaffPromoPoints;
use AppBundle\Entity\SkuCategory;
use AppBundle\Entity\InvoicePending;

class RegisterPendingController extends Controller
{

    private $moduleId = 21;

    /**
     *
     * @Route("/backend/registerPending", name="register_pending")
     */
    public function indexAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));

        $em = $this->getDoctrine()->getManager();

        // Get parameters for search
        $name = $request->get('name');
        $citizenId = $request->get('citizen_id');
        $phone = $request->get('phone');

        // Check role to see what to show
        $registerPending1 = $em->getRepository('AppBundle:RegisterPending')->getList(
                1, $phone, $citizenId, $name);
        // INFO for administrator
        $paginator = $this->get('knp_paginator');

        $pageName1 = 'page-pending';
        $pagination = $paginator->paginate($registerPending1,
                $request->query->getInt($pageName1, 1),
                $this->getParameter("number_of_rows"),
                array(
                        'pageParameterName' => $pageName1
                ));

        // Diferent roles see diferent things
        $roleId = $this->getUser()
            ->getUserRole()
            ->getId();

            /*
        if ($roleId == 3) {
            // Info for call center

            // Info for status 2
            $registerPending2 = $em->getRepository('AppBundle:RegisterPending')->getList(
                    2, $phone, $citizenId, $name);

            // INFO for administrator
            $paginator2 = $this->get('knp_paginator');

            $pageName2 = 'page-accepted';
            $pagination2 = $paginator->paginate($registerPending2,
                    $request->query->getInt($pageName2, 1),
                    $this->getParameter("number_of_rows"),
                    array(
                            'pageParameterName' => $pageName2
                    ));

            // Info for status 3
            $registerPending3 = $em->getRepository('AppBundle:RegisterPending')->getList(
                    3, $phone, $citizenId, $name);

            // INFO for administrator
            $paginator3 = $this->get('knp_paginator');

            $pageName3 = 'page-rejected';
            $pagination3 = $paginator->paginate($registerPending3,
                    $request->query->getInt($pageName3, 1),
                    $this->getParameter("number_of_rows"),
                    array(
                            'pageParameterName' => $pageName3
                    ));

            return $this->render(
                    '@App/Backend/RegisterPending/callcenter_index.html.twig',
                    array(
                            "registerPending1" => $pagination,
                            "registerPending2" => $pagination2,
                            "registerPending3" => $pagination3,
                            "permits" => $mp
                    ));
        } else {*/
            return $this->render('@App/Backend/RegisterPending/index.html.twig',
                    array(
                            "list" => $pagination,
                            "action" => "backend_register_pending_index",
                            "permits" => $mp
                    ));
        //}
    }

    /**
     *
     * @Route("/backend/registerPending/getPos", name="backend_register_pending_get_pos")
     */
    public function getPosAction (Request $request)
    {
        $posId = $request->get("posId");

        $pos = $this->getDoctrine()
            ->getRepository("AppBundle:PointOfSale")
            ->findOneBy(array(
                "pointOfSaleId" => $posId
        ));

        return new JsonResponse(
                array(
                        "groupName" => $pos->getGroupName(),
                        "pointOfSale" => $pos->getBusinessName(),
                        "address" => $pos->getAddress1(),
                        "zone" => $pos->getAddress2(),
                        "state" => $pos->getState()->getName(),
                        "city" => $pos->getCity()->getName()
                ));
    }

    /**
     *
     * @Route("/backend/registerPending/getPurshasedProductList", name="backend_register_pending_purshased_product_list")
     */
    public function getPurshasedProductListAction (Request $request)
    {
        $categoryId = $request->get("categoryId");

        $ppls = $this->getDoctrine()
            ->getRepository("AppBundle:PurchasedProductList")
            ->findBy(array(
                "purchasedProductCategory" => $categoryId
        ));

        $result = [];

        foreach ($ppls as $key => $ppl) {
            $result[$key]['id'] = $ppl->getPurchasedProductListId();
            $result[$key]['name'] = $ppl->getName();
        }

        return new JsonResponse(array(
                "data" => $result
        ));
    }

    /**
     *
     * @Route("/backend/registerPending/edit/{id}", name="backend_register_pending_edit", requirements={"id": "\d+"})
     */
    public function editAction (Request $request,
            RegisterPending $registerPending)
    {
        if ($registerPending) {

            $em = $this->getDoctrine()->getManager();

            /*
             * if( is_int($registerPending->getPointOfSale() ) ){
             * echo "entro";
             * $pointOfSaleObj =
             * $em->getRepository('AppBundle:PointOfSale')->findOneByPointOfSaleId($registerPending->getPointOfSale()->getPointOfSaleId());
             * $registerPending->setPointOfSale($pointOfSaleObj);
             * }echo "paso";
             */

            $form = $this->createForm(new RegisterPendingType(),
                    $registerPending);

            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                // si es valido o si no es valido por que no tiene punto de
                // venta

                try {
                    $save_cupon = false;
                    $cupon_points = 0;
                    if ($registerPending->getCupon() != "") {
                        $cupon = $em->getRepository('AppBundle:PromoCupon')->findOneByCode(
                                $registerPending->getCupon());
                        if (! $cupon) {
                            $this->addFlash('error_message',
                                    $this->getParameter("error_cupon_no_existe"));
                            return new Response($registerPending->getStatus());
                        }
                        if ($cupon->getStaffUsedBy() and
                                ($cupon->getStaffUsedBy()->getStaffId() > 0)) {

                            $this->addFlash('error_message',
                                    $this->getParameter("error_cupon_usado"));
                            return new Response($registerPending->getStatus());
                        }
                        $cupon_points = $registerPending->getQuantity() *
                                $cupon->getRewardCriteriaPerBag();
                        $save_cupon = true;
                    }

                    if ($request->get('pointOfSaleExists') == 1) {
                        $pointOfSale = $registerPending->getPointOfSale()->getPointOfSaleId();
                    } else {
                        $pointOfSale_form = $form->get('pointOfSale')->getData();
                        $pointOfSale = $pointOfSale_form->getPointOfSaleId();
                    }

                    if ($form->isValid() ||
                            ((! $form->isValid()) && $pointOfSale > 0)) {

                        if ($registerPending->getStatus() == 2) {

                            $error = $this->validateEditRegisterPending(
                                    $registerPending->getJobPositionId(),
                                    $registerPending, $pointOfSale);
                            if ($error) {
                                $this->addFlash('error_message', $error);
                            } else {

                                $registerPending->setUpdatedBy(
                                        $this->getUser()
                                            ->getId());
                                $registerPending->setUpdatedAt(new \DateTime());

                                $em->persist($registerPending);

                                $newStaff = new Staff();


                                $newStaff->setPointOfSale(
                                        $registerPending->getPointOfSale());
                                $newStaff->setName($registerPending->getName());
                                $newStaff->setFirstName($registerPending->getFirstName());
                                $newStaff->setLastName($registerPending->getLastName());
                                $newStaff->setTaxIdentifier(
                                        $registerPending->getTaxIdentifier());
                                $newStaff->setCitizenId(
                                        $registerPending->getCitizenId());
                                $newStaff->setGender(
                                        $registerPending->getGender());
                                if ($registerPending->getBirthdate()) {
                                    $newStaff->setBirthdate(
                                            new \DateTime(
                                                    $registerPending->getBirthdate()
                                                        ->format('Y-m-d')));
                                }
                                $newStaff->setPhoneSecondary(
                                        $registerPending->getPhoneSecondary());
                                $newStaff->setPhoneMain(
                                        $registerPending->getPhoneMain());
                                $newStaff->setEmail(
                                        $registerPending->getEmail());
                                $newStaff->setState(
                                        $registerPending->getState());
                                $newStaff->setCity($registerPending->getCity());
                                $newStaff->setCountry(
                                        $registerPending->getCountry());
                                $newStaff->setAddress1(
                                        $registerPending->getAddress1());
                                $newStaff->setJobPosition(
                                        $em->getReference(
                                                'AppBundle\Entity\JobPosition',
                                                3));
                                $newStaff->setCreatedAt(new \DateTime());
                                $newStaff->setCreatedBy(
                                        $this->getUser()
                                            ->getId());
                                $userHelper = $this->get('user.helper');
                                $newStaff->setPasswd(
                                        $userHelper->encodePassword($newStaff,
                                                $newStaff->getCitizenId()));
                                $newStaff->setProfession(
                                        $registerPending->getProfession());
                                $newStaff->setOtherProfession(
                                        $registerPending->getOtherProfession());
                                $newStaff->setSpecialty(
                                        $registerPending->getSpecialty());
                                $newStaff->setExperienceYears(
                                        $registerPending->getExperienceYears());
                                $newStaff->setCemproClub(
                                        $registerPending->getCemproClub());
                                $newStaff->setMaritalStatus(
                                        $registerPending->getMaritalStatus());
                                $newStaff->setChildNum(
                                        $registerPending->getChildNum());
                                $newStaff->setChildAge(
                                        $registerPending->getChildAge());
                                $newStaff->setReligion(
                                        $registerPending->getReligion());
                                $newStaff->setPhoneThrid(
                                        $registerPending->getPhoneThrid());
                                $newStaff->setSocial(
                                        $registerPending->getSocial());
                                $newStaff->setFreeTime(
                                        $registerPending->getFreeTime());
                                $newStaff->setZone($registerPending->getZone());
                                $newStaff->setProfileImage($registerPending->getProfileImage());

                                // Start as active staff
                                $newStaff->setStaffStatus(
                                        $em->getReference(
                                                'AppBundle\Entity\StaffStatus',
                                                2));
                                $em->persist($newStaff);


                                // insert Staff Point of sale
                                $staffPointOfSale = new StaffPointOfSale();
                                $staffPointOfSale->setStaff($newStaff);
                                $staffPointOfSale->setPointOfSale(
                                        $newStaff->getPointOfSale());
                                $staffPointOfSale->setCreatedBy(
                                        $this->getUser()
                                            ->getId());
                                $staffPointOfSale->setCreatedAt(new \DateTime());
                                $staffPointOfSale->setStatus("ACTIVE");

                                $em->persist($staffPointOfSale);
                                                                
                                                                
                                // find staff seller
                                $staffSellerArray = $em->getRepository("AppBundle:Staff")->getStaffSeller($pointOfSale);
                                if ($staffSellerArray) {
                                    $staffSeller = $em->getRepository("AppBundle:Staff")->findOneByStaffId($staffSellerArray["staff_id"]);
                                    // crear registro de 25 puntos para el vendedor
                                    $pointsToGive = 25;
                                    
                                    $saleSeller = new Sale();
                                    $saleSeller->setPointOfSale($registerPending->getPointOfSale());
                                    $saleSeller->setClientName($staffSeller->getName());
                                    $saleSeller->setClientPhone($staffSeller->getPhoneMain());
                                    $saleSeller->setIssuedAt(new \DateTime());
                                    $saleSeller->setCreatedBy($this->getUser()->getId());
                                    $invoiceNumberSeller = "REGISTRO-".date("YmdHis");
                                    $saleSeller->setInvoiceNumber($invoiceNumberSeller);
                                    $saleSeller->setQuantity(1);
                                    $saleSeller->setIsCancelled(0);                                    
                                    $em->persist($saleSeller);
                                    
                                    $saleStaffSeller = new SaleStaff();
                                    $saleStaffSeller->setStaff($staffSeller);
                                    $saleStaffSeller->setSale($saleSeller);
                                    $saleStaffSeller->setPoints($pointsToGive);
                                    $saleStaffSeller->setWasSeller(1);
                                    $saleStaffSeller->setCreatedAt(new \DateTime());
                                    $saleStaffSeller->setCreatedBy($this->getUser()->getId());
                                    $saleStaffSeller->setIsCancelled(0);
                                    $em->persist($saleStaffSeller);
                                    
                                    $accruedSeller = new AccruedPointDetails();
                                    $accruedSeller->setAccruedPoints($pointsToGive);
                                    $accruedSeller->setAvailablePoints($pointsToGive);
                                    $accruedSeller->setCreatedAt(new \DateTime());
                                    $accruedSeller->setPointType($em->getReference(
                                            'AppBundle\Entity\PointType',
                                            5));
                                    $accruedSeller->setStaff($staffSeller);
                                    $accruedSeller->setSale($saleSeller);
                                    $em->persist($accruedSeller);
                                    
                                    $invoicePendingSeller = new InvoicePending();
                                    $invoicePendingSeller->setStaff($staffSeller);
                                    $invoicePendingSeller->setInvoiceStatus("ACEPTADO");
                                    $invoicePendingSeller->setInvoiceNumber($invoiceNumberSeller);
                                    $invoicePendingSeller->setInvoiceDate(new \DateTime());
                                    $invoicePendingSeller->setComments("Puntos otorgados por nuevo registro de Bartender / Mesero");
                                    $invoicePendingSeller->setPoints($pointsToGive);
                                    $invoicePendingSeller->setCreatedAt(new \DateTime());
                                    $invoicePendingSeller->setCreatedBy($this->getUser()->getId());
                                    $em->persist($invoicePendingSeller);
                                    
                                }
                                                               
                                // guardar todos los datos
                                $em->flush();

                                $this->addFlash('success_message',
                                        $this->getParameter('exito_actualizar'));

                                $phone = "502" . $registerPending->getPhoneMain();

                                $send_sms = new SessionHelper();
                                $token = $this->getParameter("api_token");
                                $sessionHelper = new SessionHelper ();
                                $prize_amount = 5;
                                $detail = array("RECARGA_GRATIS_PRIMER_REGISTRO" => "5");
                                
                                // enviar recarga de Q5 al bartender / mesero
                                $url = "http://192.168.10.221/service-container/call/16/phone/502{$phone}/amount/{$prize_amount}/account/10/token/$token/detail/".urlencode(json_encode($detail));
                                $ws_response = $sessionHelper->open_url($url);
                                $ws_response = str_replace('""{', '"{', $ws_response);
                                $ws_response = str_replace('}""', '}"', $ws_response);
                                $resObj = json_decode($ws_response);
                                if($resObj && $resObj->status == "TRUE" ){                                    
                                    // Change error boolean
                                    $this->addFlash('success_message',
                                            $this->getParameter('exito_recarga'));
                                } else {                                    
                                    // Change error boolean
                                    $this->addFlash('error_message',
                                            $this->getParameter('error_recarga'));                                                                       
                                }
                                                                
                                // enviar mensaje de confirmacion
                                $send_sms->send_sms_televida($phone,
                                        urlencode(
                                                $this->getParameter(
                                                        'registro_pendiente_confirmado')),
                                        $token);

                                return new Response(
                                        $registerPending->getStatus());
                            }
                        } else {
                            $registerPending = $this->edit($registerPending,
                                    $registerPending->getStatus(),
                                    $registerPending->getComments(),
                                    $registerPending->getRegisterType());
                            $phone = $registerPending->getPhoneMain();
                            $send_sms = new SessionHelper();
                            $token = $this->getParameter("api_token");

                            $send_sms->send_sms_televida("502" . $phone,
                                    urlencode(
                                            $this->getParameter(
                                                    'registro_pendiente_rechazado')),
                                    $token);
                            $this->addFlash('success_message',
                                    $this->getParameter('exito_actualizar'));
                            return new Response($registerPending->getStatus());
                        }
                    } else {

                        // Error validation

                        // $debug = $form->getErrorsAsString();

                        $debug = '';
                        $this->addFlash('error_message',
                                $this->getParameter('error_form') . $debug);
                    }
                } catch (\Exception $e) {

                    $this->addFlash('error_message',
                            $this->getParameter('error') . $e->getMessage());
                    return new Response("fatal_error");
                    
                     echo $e->getMessage();
                     
                     echo "<br><br><br>";
                     
                     echo $e->getTraceAsString();
                     
                     die;
                     

                    /*
                     * error_log($e->getMessage());
                     * error_log($e->getTraceAsString());
                     */
                }
            }
            $pointOfSale = 0;
            if ($registerPending->getPointOfSale()) {
                $pointOfSale = $registerPending->getPointOfSale()->getPointOfSaleId();
            }
            return $this->render('@App/Backend/RegisterPending/edit.html.twig',
                    array(
                            "form" => $form->createView(),
                            'data_class' => 'AppBundle\Entity\RegisterPending',
                            "edit" => true,
                            "id" => $registerPending->getRegisterPendingId(),
                            "pointOfSale" => $pointOfSale
                    ));
        } else {
            $this->addFlash('error_message',
                    $this->getParameter('error_editar'));
        }
        return $this->redirectToRoute("backend_user");
    }

    public function getPromoPoints (SaleStaff $ss, $quantity, $skuCategoryId,
            $skuId)
    {
        // buscar promos activas
        $em = $this->getDoctrine()->getManager();

        // $ss =
        // $em->getRepository("AppBundle:SaleStaff")->findOneBySaleStaffId(rand(1000,20000));

        $promos = $this->getDoctrine()
            ->getRepository("AppBundle:Promo")
            ->findActivePromos();
        foreach ($promos as $activePromo) {
            $givePromoPoints = true;
            // echo $activePromo->getName() . "<br>";
            // validar si la promo aplica para el departamento del punto de
            // venta
            $states = $em->getRepository("AppBundle:PromoState")->findByPromo(
                    $activePromo);
            $validatePsStates = true;
            if ($states && count($states) > 0) {
                $validatePsStates = $em->getRepository("AppBundle:Promo")->findValidStateforPointOfSale(
                        $ss->getSale()
                            ->getPointOfSale(), $states);
            }
            if (! $validatePsStates) {
                // echo "estado no aplica<br>";
                $givePromoPoints = false;
            }
            // validar si la promo aplica para el municipio del punto de venta
            $cities = $em->getRepository("AppBundle:PromoCity")->findByPromo(
                    $activePromo);
            $validatePsCity = true;
            if ($cities && count($cities) > 0) {
                $validatePsCity = $em->getRepository("AppBundle:Promo")->findValidCityforPointOfSale(
                        $ss->getSale()
                            ->getPointOfSale(), $cities);
            }
            if (! $validatePsStates) {
                // echo "ciudad no aplica<br>";
                $givePromoPoints = false;
            }

            // validar si la promo aplica para el punto de venta
            $pointOfSales = $em->getRepository("AppBundle:PromoPointOfSale")->findByPromo(
                    $activePromo);
            $validPointOfSale = true;
            if ($pointOfSales && count($pointOfSales) > 0) {
                $validPointOfSale = $em->getRepository("AppBundle:Promo")->findValidPointOfSaleforPointOfSale(
                        $ss->getSale()
                            ->getPointOfSale(), $pointOfSales);
            }
            if (! $validPointOfSale) {
                // echo "Punto de venta no aplica<br>";
                $givePromoPoints = false;
            }

            // validar si aplica para el puesto del staff
            $jobPositions = $em->getRepository("AppBundle:PromoJobPosition")->findByPromo(
                    $activePromo);
            $validJobPosition = true;
            if ($jobPositions && count($jobPositions) > 0) {
                $validJobPosition = $em->getRepository("AppBundle:Promo")->findValidJobPosition(
                        $ss->getStaff()
                            ->getJobPosition(), $jobPositions);
            }
            if (! $validJobPosition) {
                // echo "Puesto Invalido<br>";
                $givePromoPoints = false;
            }

            // validar categoria de material
            $skuCategories = $em->getRepository("AppBundle:PromoSkuCategory")->findByPromo(
                    $activePromo);
            $validSkuCategory = true;
            if ($skuCategories && count($skuCategories) > 0) {
                $validSkuCategory = $em->getRepository("AppBundle:Promo")->findValidSkuCategory(
                        $skuCategoryId, $skuCategories);
            }
            if (! $validSkuCategory) {
                // echo "Categoria de Material Invalida<br>";
                $givePromoPoints = false;
            }

            // validar sub categoria si pertenece a mezclas listas
            if ($skuCategoryId == 2 || count($skuCategories) == 0) {
                $skus = $em->getRepository("AppBundle:PromoSku")->findByPromo(
                        $activePromo);
                $validSku = true;
                if ($skus && count($skus) > 0) {
                    $validSku = $em->getRepository("AppBundle:Promo")->findValidSku(
                            $skuId, $skus);
                }
                if (! $validSku) {
                    // echo "Sub Categoria de Material Invalida<br>";
                    $givePromoPoints = false;
                }
            }

            // TODO: Validar categorias de la promo para futuras opciones
            // validar tipo de premio

            if ($givePromoPoints) {
                // echo "entregar puntos";
                $promoPrize = $em->getRepository("AppBundle:PromoPrize")->findOneByPromo(
                        $activePromo);
                $spp = new StaffPromoPoints();
                $spp->setCreatedAt(new \DateTime());
                $spp->setPromo($activePromo);
                $spp->setSale($ss->getSale());
                $spp->setStaff($ss->getStaff());
                $skucat = $em->getRepository("AppBundle:SkuCategory")->findOneBySkuCategoryId(
                        $skuCategoryId);
                $spp->setSkuCategory($skucat);
                $skuVal = $em->getRepository("AppBundle:Sku")->findOneBySkuId(
                        $skuId);
                $spp->setSku($skuVal);
                if ($promoPrize->getPromoPrizeType()->getPromoPrizeTypeId() == 1) {
                    // dar premio por unidad
                    $pPoints = $promoPrize->getPoints();
                    $spp->setPoints($pPoints);
                } else 
                    if ($promoPrize->getPromoPrizeType()->getPromoPrizeTypeId() ==
                            2) {
                        // dar premio por factor
                        $pPoints = $promoPrize->getFactor() * $quantity;
                        $spp->setPoints($pPoints);
                    }
                $em->persist($spp);

                // crear registro en accrued point detail
                if ($skuCategoryId == 1)
                    $point_type = $em->getRepository('AppBundle:PointType')->findOneByPointTypeId(
                            "5");
                else 
                    if ($skuCategoryId == 2)
                        $point_type = $em->getRepository('AppBundle:PointType')->findOneByPointTypeId(
                                "6");

                $accrued = new AccruedPointDetails();
                $accrued->setSale($ss->getSale());
                $accrued->setAccruedPoints($pPoints);
                $accrued->setAvailablePoints($pPoints);
                $accrued->setStaff($ss->getStaff());
                $accrued->setCreatedAt(new \DateTime());
                $accrued->setPointType($point_type);
                $em->persist($accrued);

                $em->flush();

                return $pPoints;
            }
        }
        return false;
    }

    /**
     * Validates the information of a Register pending
     *
     * @param
     *            string jobPositionId the email before editing
     * @return string error message. NULL if no error
     */
    public function validateEditRegisterPending ($jobPositionId,
            $registerPending, $pos)
    {
        $em = $this->getDoctrine()->getManager();

        /*
         * $original =
         * $em->getRepository('AppBundle:JobPosition')->findOneByName($jobPositionId);
         *
         * if (count($original) ==0) {
         * // Email already in database
         * return $this->getParameter('error_puesto_trabajo');
         * }
         */

        // $original =
        // $em->getRepository('AppBundle:Staff')->findOneBy(array('taxIdentifier'=>$registerPending->getTaxIdentifier()));

        if (! $registerPending->getStaffId()) {
            $original = $em->getRepository('AppBundle:Staff')->findOneBy(
                    array(
                            'citizenId' => $registerPending->getCitizenId()
                    ));
            if (count($original) > 0) {
                // Email already in database
                return $this->getParameter('usuario_existente_rechazarlo');
            }
            $original = $em->getRepository('AppBundle:Staff')->findOneBy(
                    array(
                            'phoneMain' => $registerPending->getPhoneMain()
                    ));
            if (count($original) > 0) {
                // Email already in database
                return $this->getParameter('usuario_existente_rechazarlo');
            }
        }

        return null;
    }

    public function edit ($registerPending, $status, $comments, $registerType)
    {
        $em = $this->getDoctrine()->getManager();
        $registerPending->setStatus($status);
        $registerPending->setComments($comments);
        $registerPending->setUpdatedBy($this->getUser()
            ->getId());
        $registerPending->setUpdatedAt(new \DateTime());
        $registerPending->setRegisterType($registerType);
        
        //TODO(JCA) El guardado automático en symfony 3 funciona sin tener que hacer esto
        /*
        foreach ($registerPending->getPurchasedProductDetails() as $purchasedProductDetail) {
            $purchasedProductDetail->setCreatedAt(new DateTime());
            $purchasedProductDetail->setRegisterPending($registerPending);
            $em->persist($purchasedProductDetail);
        }
        */
        
        
        // Store in DB
        $em->persist($registerPending);
        $em->flush();
        
        return $registerPending;
    }
}
