<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ParserRegister;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Repository\EbClosion;


class ParserRegisterController extends Controller {
    
    private $moduleId = 4;
    
    
    /**
     *  @Route("/backend/parser-register/download/{id}",
     *      name="backend_parser_register_download", requirements={"id": "\d+"})
     */
    public function downloadFileAction(Request $request, ParserRegister $parserRegister){
        // Get file folder
        $typeId = $parserRegister->getParserType()->getParserTypeId();
        if ($typeId == 1) {
            $fileDir = $this->getParameter('sale_upload_folder');
        }
        else if ($typeId == 2) {
            $fileDir = $this->getParameter('point_of_sale_upload_folder');
        }
        else if ($typeId == 3) {
            $fileDir = $this->getParameter('staff_upload_folder');
        }
        else {
            throw $this->createNotFoundException('El archivo no existe');
        }

        $filename = $parserRegister->getFileName();

        // Create file path
        $path = $fileDir . "/" . $filename;

        // Get only filename
        try {
            $pathParts = pathinfo($path);
            $oFilename =  $pathParts['filename'];

            $obj = $this->get('phpexcel')->createPHPExcelObject($path);
            $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
            ob_start();
            $objWriter->save('php://output');

            return new Response(
                ob_get_clean(),  // read from output buffer
                200,
                array(
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="' . $oFilename . '.xlsx"',
                )
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException('No se encontró el archivo');
        }
        
        return sfView::NONE;
    }

    /**
     *  @Route("/backend/parser-register", name="backend_parser_register")
     */
    public function indexAction(Request $request) {
    	
    	$this->get("session")->set("module_id", $this->moduleId);
    	
        $em = $this->getDoctrine()->getManager();
        $parserRegisters = $em->getRepository ( 'AppBundle:ParserRegister')->findAll();
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate (
            $parserRegisters, $request->query->getInt ('page', 1),
            $this->getParameter ( "number_of_rows" )
        );

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
        return $this->render ( '@App/Backend/ParserRegister/index.html.twig', array(
                "parserRegisters" => $pagination,
                "permits" => $mp
        ) );
    }

}