<?php
namespace AppBundle\Controller\Backend;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\SessionHelper;

class DashboardController extends Controller
{

    private $moduleId = 18;

    /**
     *
     * @Route("/backend/dashboard", name="backend_dashboard")
     */
    public function dashboardAction (Request $request)
    {
               
        $this->get("session")->set("module_id", $this->moduleId);

        $mp = EbClosion::getModulePermission($this->moduleId,
                $this->get("session")->get("userModules"));
        $em = $this->getDoctrine()->getManager();

        $qty_invoices = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyInvoices();
        $qty_contacts = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyContacts();
        $qty_processed = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyProcessed();
        $qty_winned = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyWinned();
        $qty_rejected = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyRejected();
        $qty_changed = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyChanged();
        $qty_pending = $em->getRepository("AppBundle:InvoiceWhatsapp")->getQtyPending();
        $listProcesados = $this->getDoctrine()->getRepository("AppBundle:InvoiceWhatsapp")->getInvProcessed();
        return $this->render('@App/Backend/Dashboard/index.html.twig',
                array(
                        "permits" => $mp,
                        "qtyInvoices" => $qty_invoices,
                        "qtyContacts" => $qty_contacts,
                        "qtyProcessed" => $qty_processed,
                        "qtyWinned" => $qty_winned,
                        "qtyRejected" => $qty_rejected,
                        "qtyChanged" => $qty_changed,
                        "qtyPending" => $qty_pending
                ));
    }




}
