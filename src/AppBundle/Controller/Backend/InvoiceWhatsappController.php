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
use AppBundle\Entity\InvoiceWhatsapp;
use AppBundle\Form\InvoiceWhatsappType;
use AppBundle\Form\StaffType;
use AppBundle\Entity\StaffCode;


class InvoiceWhatsappController extends Controller
{

    private $moduleId = 18;

    /**
     *
     * @Route("/backend/invoice-whatsapp", name="backend_invoice_whatsapp")
     */
    public function indexAction (Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        $em = $this->getDoctrine()->getManager();

        $list = $em->getRepository("AppBundle:InvoiceWhatsapp")->findBy(
                array(
                        "status" => 1
                ));
        
       $listProcesados = $this->getDoctrine()->getRepository("AppBundle:InvoiceWhatsapp")->getInvProcessed();
        return $this->render('@App/Backend/InvoiceWhatsapp/index.html.twig',
                array(
                        "list" => $list,
                        "listProcesados" => $listProcesados,
                        "permits" => $mp
                ));
    }


    /**
     *
     * @Route("/backend/invoice-whatsapp-pending/review/{id}", name="backend_invoice_whatsapp_pending_review", requirements={"id": "\w+"})
     */
    public function editAction (Request $request)
    {
        $iwId = $request->get('id');
        $iwStaffObj = $this->getDoctrine()->getRepository("AppBundle:InvoiceWhatsapp")->findOneBy(array("invoiceId"=>$iwId));
        $imgName = $iwStaffObj->getImageName();
        $recurrent = $iwStaffObj->getRecurrent();
        $createdAt = $iwStaffObj->getCreatedAt();
        $stId = $iwStaffObj->getStaff()->getStaffId();
        $form = $this->createForm ( new InvoiceWhatsappType (), $iwStaffObj);
        $form->handleRequest ( $request );
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                
                $staffData = $request->get('staff');
                $iwsData = $request->get('appbundle_invoicewhatsapp');

                $em = $this->getDoctrine()->getManager();
                $iwEnt = $em->getRepository("AppBundle:InvoiceWhatsapp")->findOneBy(array("invoiceId"=>$iwId));
                $newStatus = $em->getRepository("AppBundle:MainStatus")->findOneBy(array("mainStatusId"=>$iwsData['status']));
                $staffEnt = $em->getRepository("AppBundle:Staff")->findOneBy(array("staffId"=>$stId));

                //AQUI SE ESTA OBTENIEDO EL OBJETO DE PREMIO QUE CORRESPONDE CON EL GRANDE O PEQUEÑO. PRIZE_TYPE = 1 ES PREMIO GRANDE, 0 ES PEQUEÑO
                if ($iwsData['prizeType'] == '1') {
                    $prizeObj = $em->getRepository("AppBundle:Prize")->findOneBy(array("id"=>"26")); 
                }else{
                    $prizeObj = $em->getRepository("AppBundle:Prize")->findOneBy(array("id"=>"27"));
                }
                
                if ($iwsData['productQuantity'] == '') {
                    $pqty = 0;
                }else{
                    $pqty = $iwsData['productQuantity'];
                }
                if ($iwsData['status'] == 3) {
                    $iwEnt->setNotifiStatus('pendiente');
                }
                $iwEnt->setProductQuantity($pqty);
                $iwEnt->setImageName($imgName);
                $iwEnt->setInvoiceNumber($iwsData['invoiceNumber']);
                $iwEnt->setNit($iwsData['nit']);
                $iwEnt->setTotalInvoice($iwsData['totalInvoice']);
                $iwEnt->setPrizeType($iwsData['prizeType']);    
                $iwEnt->setRecurrent($recurrent);
                $iwEnt->setCreatedAt($createdAt);
                $iwEnt->setUpdatedAt(new \DateTime());
                $iwEnt->setStaff($staffEnt);
                $iwEnt->setStatus($newStatus);
                $iwEnt->setRejectionMessage($iwsData['rejectionMessage']);
                $em->persist($iwEnt);
                
                $newCountry = $em->getRepository("AppBundle:Country")->findOneBy(array("id"=>$staffData['country']));
                $staffEnt->setName($staffData['name']);
                $staffEnt->setCitizenId($staffData['citizenId']);
                $staffEnt->setEmail($staffData['email']);
                $staffEnt->setCountry($newCountry);
                $em->persist($staffEnt);

                if ($iwsData['status'] == 2) {
                    $winnCode = strtoupper("T".substr( md5(microtime()), 1, 5));
                    $codeStatusObj = $em->getRepository("AppBundle:CodeStatus")->findOneBy(array("codeStatusId"=>"2"));
                    $storeObj = $em->getRepository("AppBundle:Store")->findOneBy(array("id"=>"54"));
                    $staffCodeObj = new StaffCode();
                    $staffCodeObj->setCode($winnCode);
                    $staffCodeObj->setStaff($staffEnt);
                    $staffCodeObj->setCodeStatus($codeStatusObj);
                    $staffCodeObj->setCreatedAt(new \DateTime());
                    $staffCodeObj->setPrize($prizeObj);
                    $staffCodeObj->setWhatsappStatus('pendiente');
                    $staffCodeObj->setStore($storeObj); //ID = 54     STORE_NAME = TIENDA DE PRODUCTOS LALA
                    $em->persist($staffCodeObj);
                }

                $em->flush();
                
                $this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
                return $this->redirectToRoute ( "backend_invoice_whatsapp" );
            }else{

                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
                return $this->redirectToRoute ( "backend_invoice_whatsapp" );
            }
        }
        $staffObj = $this->getDoctrine()->getRepository("AppBundle:Staff")->findOneBy(array("staffId"=>$iwStaffObj->getStaff()->getStaffId()));
        $formStaff = $this->createForm(new StaffType(),$staffObj);
        $statusAct = $iwStaffObj->getStatus()->getMainStatusId();
        $statusObj = $this->getDoctrine()->getRepository("AppBundle:MainStatus")->findBy(array("forTable"=>"INVOICE_WHATSAPP"));
        $statusOpt = [];
        foreach ($statusObj as $value) {
            $statusOpt[$value->getMainStatusId()]['id'] = $value->getMainStatusId();
            $statusOpt[$value->getMainStatusId()]['name'] = $value->getName(); 
        }
        $countryAct = $staffObj->getCountry()->getId();
        
        $countryObj = $this->getDoctrine()->getRepository("AppBundle:Country")->findAll();
        $countryOpt = [];
        foreach ($countryObj as $value) {
            $countryOpt[$value->getId()]['id'] = $value->getId();
            $countryOpt[$value->getId()]['name'] = $value->getName(); 
        }

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(  'SELECT p
                                                FROM AppBundle:InvoiceWhatsapp p
                                                WHERE p.status > :sta')->setParameter('sta', 1);
        $listProcesados = $query->getResult();
        return $this->render('@App/Backend/InvoiceWhatsapp/edit.html.twig',
            array(  "formInvoice" => $form->createView (),
                    "formStaff" => $formStaff->createView(),
                    "imgName" => $iwStaffObj->getImageName(),
                    "countryAct" => $countryAct,
                    "statusAct" => $statusAct,
                    "statusOpt" => $statusOpt,
                    "countryOpt" => $countryOpt,
                    "recurrent" => $recurrent,
                    "listProcesados" => $listProcesados
            ));
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


    /**
     *
     * @Route("/backend/invoice-verify", name="backend_invoice_verify")
     */
    public function invoiceVerifyAction (Request $request)
    {
        $myInvoice = $request->get("myInvoice");
        if ($myInvoice) {
            $mInvObj = $this->getDoctrine()
                ->getRepository("AppBundle:InvoiceWhatsapp")
                ->findOneBy(array(
                    "invoiceNumber" => $myInvoice
            ));
            if ( is_null($mInvObj) ) {
                $invNone = 'none';
            }else{
                $invNone = 'exist';
            }
            return new JsonResponse(array(
                    "invoice" => $invNone
            ));
        } else {
            return new JsonResponse(array(
                    false
            ));
        }
    }

}
