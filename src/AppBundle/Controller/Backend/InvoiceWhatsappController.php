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
                        "status" => 5
                ));
       
        $listAceptado = $em->getRepository("AppBundle:InvoiceWhatsapp")->findBy(
                array(
                        "status" => 6
                ));
        
        $listRechazado = $em->getRepository("AppBundle:InvoiceWhatsapp")->findBy(
                array(
                        "status" => 8
                ));

        return $this->render('@App/Backend/InvoiceWhatsapp/index.html.twig',
                array(
                        "list" => $list,
                        "listAceptado" => $listAceptado,
                        "listRechazado" => $listRechazado,
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

        $iwObj = new InvoiceWhatsapp();
        $form = $this->createForm ( new InvoiceWhatsappType (), $iwObj);
        $form->handleRequest ( $request );
        $staffObj = $this->getDoctrine()->getRepository("AppBundle:Staff")->findOneBy(array("staffId"=>$iwStaffObj->getStaff()->getStaffId()));
        $formStaff = $this->createForm(new StaffType(),$staffObj);
        return $this->render('@App/Backend/InvoiceWhatsapp/form.html.twig',
            array(  "message" => "Hola de nuevo",
                    "formInvoice" => $form->createView (),
                    "formStaff" => $formStaff->createView(),
                    "imgName" => $iwStaffObj->getImageName()
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
}
