<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Sale;
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
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class SaleStaffController extends Controller
{
    /**
     * @Route("/backend/sale/cancell", name="backend_sale_cancell")
     */
    public function cancellAction(Request $request) {
        if ($this->getUser()->getUserRole()->getId() != 1) {
            return new JsonResponse(array(
                'status' => 403,
                'message' => 'No tiene permisos para esta página'
            ));
        }

        //$saleStaff = new SaleStaff();
        //$form = $this->createForm (new SaleStaffCancellType (), $saleStaff);

        // Process form
        //$form->handleRequest($request);

        /* VIENE DE OTROS FORMULARIOS
        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse(array(
                'status' => 500,
                'message' => $this->getParameter('error_form')
            ));
        }
        */

        //$skuCode = trim($saleStaff->getSmsString());
        $skuCode = $request->get("smsString", 0);

        // Look for sale
        $em = $this->getDoctrine()->getManager();
        $sale = $em->getRepository('AppBundle:Sale')->findOneBySkuCode($skuCode);

        if (!$sale) {

            $this->addFlash ( 'error_message', 'No se encontró una venta con motor No. ' . $skuCode );

            return new JsonResponse(array(
                'status' => 500,
                'message' => 'No se encontró una venta con motor No. ' . $skuCode
            ));
        } else {
            // marcar como negativa
            $sale->setIsCancelled(1);
            $sale->setSkuCode($sale->getSkuCode() . "-" . "CANCELADO");
            $em->persist($sale);

            // Buscar en reversa y actualizar registro
            $reverse = $em->getRepository('AppBundle:SaleReverse')->findOneBySkuCode($skuCode);
            $reverse->setStatus("APLICADO");
            $em->persist($reverse);
        }

        // Look for not cancelled sale staffs
        $saleStaffs = $em->getRepository('AppBundle:SaleStaff')
            ->findBy(array(
                'isCancelled' => 0,
                'originalSaleStaff' => NULL,
                'sale' => $sale
            ));

        /* LA VENTA SE PUEDE REVERTIR INCLUSO SI NO SE HA REPORTADO
        if (!$saleStaffs || sizeof($saleStaffs) == 0) {
            return new JsonResponse(array(
                'status' => 500,
                'message' => 'No se encontró una venta registrada con motor No. ' . $skuCode
            ));
        }
        */

        // Set as cancelled the SMS incoming
        $smsIncoming = $em->getRepository('AppBundle:SmsIncoming')
            ->findOneBy(
                array(
                    'smsString' => $skuCode,
                    'alreadyParsed' => 1,
                    'isCancelled' => 0
                ),
                array(
                    'createdAt' => 'DESC'
                )
            );
        if ($smsIncoming) {
            $smsIncoming->setIsCancelled(1);
            $em->persist($smsIncoming);
        }

        foreach ($saleStaffs as $originalSs) {
            // Create a new one with negative points
            $negativeSs = clone $originalSs;
            $negativeSs->setCreatedAt(new \DateTime());
            $negativeSs->setPoints($negativeSs->getPoints() * -1);
            $negativeSs->setCreatedBy($this->getUser()->getId());
            $negativeSs->setOriginalSaleStaff($originalSs);
            $negativeSs->setIsCancelled(1);

            $em->persist($negativeSs);

            // Set original sale as cancelled
            $originalSs->setIsCancelled(1);
            $em->persist($originalSs);
        }

        $em->flush();

        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_reverse' ) );

        return new JsonResponse(array(
            'status' => 200,
            'message' => 'Su venta ha sido cancelada'
        ));
    }
}
