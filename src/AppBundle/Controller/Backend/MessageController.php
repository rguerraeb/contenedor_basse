<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Message;
use AppBundle\Entity\MessageLog;
use AppBundle\Entity\Staff;
use AppBundle\Form\MessageStaffType;
use AppBundle\Form\MessageType;
use AppBundle\Form\MessageConfirmType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends Controller
{
    private $moduleId = 15;

    /**
     * @Route("/backend/message", name="backend_message"
     * )
     */
    public function indexAction(Request $request) {
        $this->get("session")->set("module_id", $this->moduleId);

        $em = $this->getDoctrine()->getManager();

        if(in_array ( "ROLE_ADMINISTRADOR", $this->getUser()->getRoles() ))
        {
                $queryMessages = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findAll ( );
                $codeStatus = $this->getDoctrine ()->getRepository ( 'AppBundle:CodeStatus' )->findOneBy ( array (
                        "codeStatusId" => 1 
                ) );
                $qtyMessagesDisponibles = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findBy ( array (
                    "codeStatus" => $codeStatus) );
                $codeStatus = $this->getDoctrine ()->getRepository ( 'AppBundle:CodeStatus' )->findOneBy ( array (
                        "codeStatusId" => 2 
                ) );
                $qtyMessagesCanjeados = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->findBy ( array (
                    "codeStatus" => $codeStatus) );
                
                $qtyParticipantes = $this->getDoctrine ()->getRepository ( 'AppBundle:StaffCode' )->getTotalParticipants (  );
                if ($qtyParticipantes)
                    $qtyParticipantes = $qtyParticipantes['participants'];
                else
                $qtyParticipantes = 0 ;
                
                

        }        
        // Paginate messages
        //$paginator = $this->get ( 'knp_paginator' );
        //$pagination = $paginator->paginate ( $queryMessages, $request->query->getInt ( 'page', 1 ), $this->getParameter ( "number_of_rows" ) );
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render (
            '@App/Backend/Message/index.html.twig',
            array(
                "list" => $queryMessages,
                "permits" => $mp,
                'disponibles' => count($qtyMessagesDisponibles),
                'canjeados' => count($qtyMessagesCanjeados),
                'participantes' =>$qtyParticipantes
            )
        );
    }
}