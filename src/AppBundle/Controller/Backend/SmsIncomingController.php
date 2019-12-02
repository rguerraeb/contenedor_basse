<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\SmsIncoming;
use AppBundle\Entity\Staff;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;

class SmsIncomingController extends Controller
{
    private $moduleId = 19;

    /**
     * @Route("/backend/sms-incoming", name="backend_sms_incoming")
     */
    public function indexAction(Request $request)
    {
        $this->get("session")->set("module_id", $this->moduleId);
        $em = $this->getDoctrine()->getManager();

        // Default values for search query
        $phone = $request->get('phone');
        $sms = $request->get('sms');

        $smsIncomings = $em->getRepository ( 'AppBundle:SmsIncoming')->search($sms, $phone);
        $paginator = $this->get ( 'knp_paginator' );

        $pagination = $paginator->paginate (
            $smsIncomings,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows" )
        );

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
        
        return $this->render ( '@App/Backend/SmsIncoming/index.html.twig', array(
                "smsIncomings" => $pagination,
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/sms-incoming/view/{id}", 
     *  name="backend_sms_incoming_view",
     *  requirements={"id": "\d+"}
     * )
     */
    public function viewAction(Request $request, SmsIncoming $staff)
    {
        return $this->render ( '@App/Backend/SmsIncoming/view.html.twig', array(
                "smsIncoming" => $staff
        ) );
    }

    /**
     * @Route(
     *  "/backend/sms-incoming/staff/{id}",
     *  name="backend_sms_incoming_staff",
     *  requirements={"id": "\d+"}
     * )
     */
    public function staffAction(Request $request, Staff $staff)
    {
        // Get parameters for serach
        $createdAt = $request->get('createdAt');
        $phone = $request->get('phone');
        $smsString = $request->get('smsString');

        $em = $this->getDoctrine()->getManager();
        $smsIncomings = $em->getRepository('AppBundle:SmsIncoming')->getByStaff(
            $createdAt, $phone, $smsString, $staff
        );

        // Transform to remove objects
        $smsIncomingsAr = array();
        foreach ($smsIncomings as $smsIncoming) {
            $smsIncomingsAr[] = array(
                $smsIncoming['id'],
                $smsIncoming['createdAt']->format('Y-m-d H:i'),
                $smsIncoming['phone'],
                $smsIncoming['smsString'],
                $smsIncoming['parseResult'],
            );
        }

        return new JsonResponse(array(
            'status' => 200,
            'data' => $smsIncomingsAr
        ));
    }
}
