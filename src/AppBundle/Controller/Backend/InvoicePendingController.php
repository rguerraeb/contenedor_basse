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
use AppBundle\Form\InvoicePendingType;
use AppBundle\Entity\PurchasedProductDetail;

class InvoicePendingController extends Controller
{

    private $moduleId = 31;

    /**
     *
     * @Route("/backend/invoice-pending", name="backend_invoice_pending")
     */
    public function indexAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "invoiceStatus" => "PENDIENTE"
                ));
       
        $listAceptado = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "invoiceStatus" => "ACEPTADO"
                ));
        
        $listRechazado = $em->getRepository("AppBundle:InvoicePending")->findBy(
                array(
                        "invoiceStatus" => "RECHAZADO"
                ));

        return $this->render('@App/Backend/InvoicePending/index.html.twig',
                array(
                        "list" => $list,
                        "listAceptado" => $listAceptado,
                        "listRechazado" => $listRechazado,
                        "permits" => $mp
                ));
    }

    /**
     *
     * @Route("/backend/invoice-pending/review/{id}", name="backend_invoice_pending_review", requirements={"id": "\w+"})
     */
    public function editAction (Request $request)
    {
        $md5Ip = $request->get("id", false);

        $em = $this->getDoctrine()->getManager();

        $ipId = $em->getRepository("AppBundle:InvoicePending")->findOneByMd5Id(
                $md5Ip);
        $ip = $em->getRepository("AppBundle:InvoicePending")->findOneBy(
                array(
                        "invoicePendingId" => $ipId
                ));

        // point of sale active
        $pos = $em->getRepository("AppBundle:StaffPointOfSale")->findOneBy(
                array(
                        "staff" => $ip->getStaff(),
                        "status" => "ACTIVE"
                ), array(
                        "createdAt" => "DESC"
                ));

        if (! $pos) {
            $this->addFlash('error_message',
                    $this->getParameter('no_pos_error_msg'));
            return $this->redirectToRoute("backend_invoice_pending");
        }

        if ($ip) {

            $form = $this->createForm(new InvoicePendingType(), $ip);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {

                    // si es valido o si no es valido por que no tiene punto de
                    // venta

                    try {

                        if ($ip->getInvoiceStatus() == 'PENDIENTE') {     
                            
                            // validar No. de Factura                            
                            $findInvoice = $em->getRepository("AppBundle:Sale")->findOneBy(array("invoiceNumber" => $ip->getInvoiceNumber()));
                            if ($findInvoice) {
                                $this->addFlash('error_message',
                                        $this->getParameter(
                                                'factura_existente_rechazarlo'));
                                        return $this->redirectToRoute(
                                                "backend_invoice_pending_review", array("id" => md5($ip->getInvoicePendingId())));
                            }
                        

                            // create sale
                            $sale = new Sale();
                            $sale->setIsCancelled(0);
                            $sale->setCreatedBy($this->getUser()
                                ->getId());
                            $sale->setPointOfSale($pos->getPointOfSale());

                            $sale->setClientName($ip->getStaff()
                                ->getName());
                            $sale->setClientPhone(
                                    $ip->getStaff()
                                        ->getPhoneMain());
                            $sale->setIssuedAt(new \DateTime());
                            $sale->setCreatedBy($this->getUser()
                                ->getId());
                            $sale->setInvoiceNumber($ip->getInvoiceNumber());

                            $detailPoints = 0;
                            $totalProductDetail = 0;
                            $products = $request->get("prod", false);

                            foreach ($products as $item) {

                                // created ppd entity
                                $purchasedProductDetail = new PurchasedProductDetail();
                                // find sku
                                $sku = $em->getRepository("AppBundle:Sku")->findOneBySkuId(
                                        $item["sku"]);
                                $purchasedProductDetail->setSku($sku);
                                $purchasedProductDetail->setCreatedAt(
                                        new DateTime());
                                $purchasedProductDetail->setSale($sale);
                                $purchasedProductDetail->setPurchasedProductCategory($em->getReference(
                                        'AppBundle\Entity\PurchasedProductCategory',
                                        $item["presentation"]));

                                // get filter group by type of product
                                $errorSkuType = false;
                                if ($item["presentation"] == 2) {
                                    // BOTELLA
                                    if ($sku->getModel() == "PREMIUM") {
                                        $filterGroup = 1;
                                        $point_type = 1;
                                    } elseif ($sku->getModel() == "OTROS") {
                                        $filterGroup = 3;
                                        $point_type = 3;
                                    } else {
                                        $errorSkuType = true;
                                    }
                                } elseif ($item["presentation"] == 1) {
                                    // TRAGO
                                    if ($sku->getModel() == "PREMIUM") {
                                        $filterGroup = 2;
                                        $point_type = 2;
                                    } elseif ($sku->getModel() == "OTROS") {
                                        $filterGroup = 4;
                                        $point_type = 4;
                                    } else {
                                        $errorSkuType = true;
                                    }
                                } else {
                                    $errorSkuType = true;
                                }
                                if ($errorSkuType) {
                                    // no se encontro el criterio de
                                    // recompenza para este producto,
                                    // redirect
                                    $this->addFlash('error_message',
                                            $this->getParameter(
                                                    'reward_criteria_error'));
                                    return $this->redirectToRoute(
                                            "backend_invoice_pending");
                                }

                                // get point for the items
                                $rewardCriteria = $this->getDoctrine()
                                    ->getRepository("AppBundle:RewardCriteria")
                                    ->findOneByFilterGroup($filterGroup);

                                $totalProductDetail += $item["quantity"];
                                $detailPoints += number_format(
                                        $item["quantity"] *
                                        $rewardCriteria->getMathematicalOperator(),
                                        2, ".", "");

                                $purchasedProductDetail->setRewardCriteria(
                                        $rewardCriteria->getMathematicalOperator());

                                $totalDetailPoints = number_format(
                                        $item["quantity"] *
                                        $rewardCriteria->getMathematicalOperator(),
                                        2, ".", "");
                                $purchasedProductDetail->setTotalPoints(
                                        $totalDetailPoints);
                                $purchasedProductDetail->setAmount(
                                        $item["quantity"]);
                                $purchasedProductDetail->setValue(0);

                                $em->persist($purchasedProductDetail);

                                // Guardar puntos en accrued point detail
                                $accruedCupones = new AccruedPointDetails();
                                $accruedCupones->setSale($sale);
                                $accruedCupones->setAccruedPoints(
                                        $totalDetailPoints);
                                $accruedCupones->setAvailablePoints(
                                        $totalDetailPoints);
                                $accruedCupones->setStaff($ip->getStaff());
                                $accruedCupones->setCreatedAt(new \DateTime());
                                $accruedCupones->setPointType(
                                        $em->getReference(
                                                'AppBundle\Entity\PointType',
                                                $point_type));
                                $em->persist($accruedCupones);
                            }

                            // ACTUALIZAR DATOS DE FACTURA
                            $ip->setPoints($detailPoints);
                            $ip->setUpdatedBy($this->getUser()
                                ->getId());
                            $ip->setUpdatedAt(new \DateTime());
                            $ip->setInvoiceStatus("ACEPTADO");
                            $em->persist($ip);

                            $sale->setQuantity($totalProductDetail);
                            $em->persist($sale);

                            // insert in sale_staff to get points to client
                            $ss = new SaleStaff();
                            $ss->setStaff($ip->getStaff());
                            $ss->setSale($sale);
                            $ss->setPoints($detailPoints);
                            $ss->setWasSeller(1);
                            $ss->setCreatedAt(new \DateTime());
                            $ss->setCreatedBy($this->getUser()
                                ->getId());
                            $ss->setIsCancelled(0);
                            $em->persist($ss);
                            $em->flush();
                            
                            
                            // send points message
                            $messagePoints = $this->getParameter(
                                    'sms_puntos_ganados');
                            $messagePoints = str_replace('[FACTURA]',
                                    $sale->getInvoiceNumber(), $messagePoints);
                            $messagePoints = str_replace('[PUNTOS]',
                                    $ss->getPoints(), $messagePoints);
                            
                            // TODO: definir promos
                            $promoTotal = 0;
                            $totalPoints = $this->getDoctrine()
                            ->getRepository("AppBundle:Staff")
                            ->getPointSummary($ss->getStaff()->getStaffId());
                            // enviar total sin los puntos promo
                            $total = $totalPoints["disponibles"] - $promoTotal;
                            $messagePoints = str_replace('[TOTAL_PUNTOS]',
                                    $total, $messagePoints);
                            
                            $send_sms = new SessionHelper();
                            $token = $this->getParameter("api_token");
                            $phone = $ip->getStaff()->getPhoneMain();
                            $send_sms->send_sms_televida("502" . $phone,
                                    urlencode($messagePoints),
                                    $token);

                            // redireccionar al listado
                            $this->addFlash('success_message',
                                    $this->getParameter('exito'));
                            return $this->redirectToRoute(
                                    "backend_invoice_pending");
                        } elseif ($ip->getInvoiceStatus() == 'RECHAZADO') {
                            
                            $ip->setPoints(0);
                            $ip->setUpdatedBy($this->getUser()
                                ->getId());
                            $ip->setUpdatedAt(new \DateTime());
                            $em->flush();

                            $phone = $ip->getStaff()->getPhoneMain();

                            $send_sms = new SessionHelper();
                            $token = $this->getParameter("api_token");

                            $send_sms->send_sms_televida("502" . $phone,
                                    urlencode(
                                            $this->getParameter(
                                                    'factura_pendiente_rechazada')),
                                    $token);
                            $this->addFlash('success_message',
                                    $this->getParameter('exito_actualizar'));
                        } else {
                            // ninguno de los anteriores redireccionar al
                            // listado
                            $this->addFlash('error_message',
                                    $this->getParameter('error') .
                                    $e->getMessage());
                            return $this->redirectToRoute(
                                    "backend_invoice_pending");
                        }

                        return $this->redirectToRoute("backend_invoice_pending");
                    } catch (\Exception $e) {

                        $this->addFlash('error_message',
                                $this->getParameter('error') . $e->getMessage());
                        
                        return $this->redirectToRoute(
                                "backend_invoice_pending_review", array("id" => md5($ip->getInvoicePendingId())));
                    }
                } else {
                    $this->addFlash('error_message',
                            $this->getParameter('error_form') . $e->getMessage());
                    
                    return $this->redirectToRoute(
                            "backend_invoice_pending_review", array("id" => md5($ip->getInvoicePendingId())));
                }
            }

            // sku
            $skuList = $em->getRepository("AppBundle:Sku")->findBy(array(),
                    array(
                            "skuFilterString" => "ASC"
                    ));

            return $this->render('@App/Backend/InvoicePending/edit.html.twig',
                    array(
                            "form" => $form->createView(),
                            "edit" => true,
                            "skuList" => $skuList,
                            "pos" => $pos
                    ));
        } else {
            $this->addFlash('error_message', $this->getParameter('error_editar'));
        }
        return $this->redirectToRoute("backend_invoice_pending");
    }

    /**
     *
     * @Route("/backend/invoice-pending/load-product-type", name="backend_invoice_pending_load_type")
     */
    public function loadProductTypeAction (Request $request)
    {
        $skuId = $request->get("sid");
        if ($skuId) {
            $sku = $this->getDoctrine()
                ->getRepository("AppBundle:Sku")
                ->findOneBy(array(
                    "skuId" => $skuId
            ));
            return new JsonResponse(array(
                    "brand" => $sku->getModel()
            ));
        } else {
            return new JsonResponse(array(
                    false
            ));
        }
    }
}
