<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AppBundle\Entity\WhatsappImages;
use AppBundle\Form\WhatsappImagesType;


class RegisterInvoiceController extends Controller
{

    private $moduleId = 18;

    /**
     * @Route("/backend/invoice", name="backend_invoice")
     */
    public function indexAction(Request $request) {

        $this->get("session")->set("module_id", $this->moduleId);

        $invoiceList = $this->getDoctrine()->getRepository('AppBundle:WhatsappImages')->findAll();

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render('@App/Backend/RegisterInvoice/index.html.twig',
        array(
                "list" => $invoiceList,
                "permits" => $mp
        ));

    }


    /**
     * @Route("/backend/invoice/register", name="backend_invoice_register")
     */
    public function registerAction(Request $request) {

        $this->get("session")->set("module_id", $this->moduleId);

        $invoiceList = $this->getDoctrine()->getRepository('AppBundle:WhatsappImages')->findAll();

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render('@App/Backend/RegisterInvoice/index.html.twig',
        array(
                "list" => $invoiceList,
                "permits" => $mp
        ));

    }


}
