<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\PointOfSale;
use AppBundle\Entity\LogParser;
use AppBundle\Entity\ParserRegister;
use AppBundle\Form\PointOfSaleFileType;
use AppBundle\Form\PointOfSaleType;
use AppBundle\Repository\EbClosion;
use AppBundle\Helper\SessionHelper;

class PointOfSaleController extends Controller
{
    
    private $moduleId = 5;
    
    /**
     * @Route("/backend/point-of-sale", name="backend_point_of_sale")
     */
    public function indexAction(Request $request)
    {
    	$this->get("session")->set("module_id", $this->moduleId);

        // Get search parameters
        $taxIdentifier = $request->get('nit');
        $name = $request->get('name');
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        // Create form
        $pointOfSale = new PointOfSale();
        $form = $this->createForm ( new PointOfSaleType (), $pointOfSale );

        $form->handleRequest ( $request );
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                $error = $this->validateEdit($pointOfSale, NULL, NULL);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {
                    $user = $this->create($pointOfSale);
                    $this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
                }
            } else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        $pointOfSales = $em->getRepository ( 'AppBundle:PointOfSale')->search($name, $taxIdentifier, $id);
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate ( $pointOfSales, $request->query->getInt ( 'page', 1 ), $this->getParameter ( "number_of_rows" ) );

        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
        return $this->render ( '@App/Backend/PointOfSale/index.html.twig', array(
                "pointOfSales" => $pagination,
                "form"=>$form->createView(),
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/point-of-sale/upload", name="backend_point_of_sale_upload")
     */
    public function uploadAction(Request $request)
    {
        // Form to upload file
        $pointOfSaleFile = new PointOfSale();
        $form = $this->createForm ( new PointOfSaleFileType (), $pointOfSaleFile );

        $em = $this->getDoctrine()->getManager();

        // Process to save file
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $error = true;
            $errors = array();

            if ($form->isValid()) {
                $file = $pointOfSaleFile->getFile();

                // Move file
                // Generate a unique name for the file before saving it
                $fileName = md5(uniqid()).'.'.$file->getClientOriginalExtension();

                // Move the file to the directory where excels are stored
                $fileDir = $this->getParameter('point_of_sale_upload_folder');
                $file->move(
                    $fileDir,
                    $fileName
                );

                // Check that file was uploaded
                $filePath = $fileDir . '/' . $fileName;

                if (file_exists($filePath)) {
                    try {
                        $pointOfSales = $this->readExcelFile($filePath, $fileName);

                        // Keep type of elements to persist
                        $type = null;
                        $logParserType = 'AppBundle\Entity\LogParser';
                        $pointOfSaleType = 'AppBundle\Entity\PointOfSale';

                        foreach ($pointOfSales as $key => $pointOfSale) {
                            // Save sale
                            if (get_class($pointOfSale) == $logParserType) {
                                array_push($errors, "Fila No. $key, " . $pointOfSale->getLogText());
                            }                                                       

                            //echo get_class($pointOfSale) . "<br>";

                            $em->persist($pointOfSale);
                            $em->flush();
                        }
                        //die;

                        if (sizeof($errors) == 0) {
                            $error = false;
                        }

                        //$em->flush();
                    } catch (Exception $e) {
                    }
                }
            }

            if ($error) {
                $errorsMess = join("<br>", $errors);
                $errorsMess = $this->container->getParameter ( 'error_sale_upload' ) .
                    "<div class='list-error'> " .
                        $errorsMess .
                    "</div>";
                
                array_unshift(
                    $errors,
                    $this->container->getParameter ( 'error_sale_upload' )
                );

                // Error message
                $this->addFlash(
                    'error_message',
                    $errorsMess
                );
            }
            else {
                // Success message
                $this->addFlash(
                    'success_message',
                    $this->container->getParameter ( 'exito' )
                );
            }
        }

        return $this->render(
            '@App/Backend/PointOfSale/upload.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/backend/point-of-sale/edit/{id}", name="backend_point_of_sale_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, PointOfSale $pointOfSale) {
        if ($pointOfSale) {
            $form = $this->createForm (new PointOfSaleType (), $pointOfSale);

            // Old info
            $oldTaxIdentifier = $pointOfSale->getTaxIdentifier();
            $oldPointOfSaleInnerId = $pointOfSale->getPointOfSaleInnerId();

            $form->handleRequest ( $request );
            if ($form->isSubmitted ()) {
                if ($form->isValid ()) {
                    $error = $this->validateEdit($pointOfSale, $oldTaxIdentifier, $oldPointOfSaleInnerId);

                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        $user = $this->edit($pointOfSale);
                        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                    }
                } else {
                    // Error validation
                    $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
                }
            }
            return $this->render ( '@App/Backend/PointOfSale/edit.html.twig', array(
                    "form" => $form->createView ()
            ) );
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }
        return $this->redirectToRoute ( "backend_point_of_sale" );
    }

    /**
     * @Route("/backend/point-of-sale/delete/{id}", name="backend_point_of_sale_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, PointOfSale $pointOfSale) {
        if ($pointOfSale) {
            // Try to delete
            try {
                $em = $this->getDoctrine ()->getManager ();

                $em->remove( $pointOfSale );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }
        
        return $this->redirectToRoute ( "backend_point_of_sale" );
    }

    public function readExcelFile($path, $fileName) {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($path);

        // Needed
        $em = $this->getDoctrine()->getManager();
        $pointOfSales = array();
        $errors = array();

        // Error stuff
        $errorType = $em->getRepository('AppBundle:ParserType')->findOneByParserTypeId(1);
        $user = $this->getUser();

        // Create Parser Register per file
        $parserRegister = new ParserRegister();
        $parserRegister->setFileName($fileName);
        $parserRegister->setCreatedAt(new \DateTime());
        $parserRegister->setCreatedBy($user);
        $parserRegister->setParserType($em->getReference('AppBundle\Entity\ParserType', 3));

        $em = $this->getDoctrine()->getManager();
        $em->persist($parserRegister);
        $em->flush();


        foreach ($phpExcelObject ->getWorksheetIterator() as $worksheet) {
            // Every worksheet
            foreach ($worksheet->getRowIterator() as $row) {
                // Every row of this worksheet
                // Each row has a sale
                $pointOfSale = new PointOfSale();
                $pointOfSale->setCreatedBy($user->getId());
                $pointOfSale->setCreatedAt(new \DateTime());
                $pointOfSale->setStatus(1);
                $pointOfSale->setParserRegister($parserRegister);
                $pointOfSale->setSaleChannel($em->getReference(
                        'AppBundle\Entity\SaleChannel',
                        1));
                $pointOfSale->setRegion($em->getReference(
                        'AppBundle\Entity\Region',
                        2));
                $pointOfSale->setState($em->getReference(
                        'AppBundle\Entity\State',
                        1));
                $pointOfSale->setCity($em->getReference(
                        'AppBundle\Entity\City',
                        77));
                $pointOfSale->setCountry($em->getReference(
                        'AppBundle\Entity\Country',
                        1));

                $cellIterator = $row->getCellIterator();

                // Loop all cells, even if it is not set
                $cellIterator->setIterateOnlyExistingCells(false);

                // Variable to check if row is empty
                $emptyRow = true;

                foreach ($cellIterator as $cell) {
                    if (!is_null($cell)) {
                        // Every valid cell of this row

                        // Name of column
                        $col = $cell->getColumn();

                        // Row number
                        $row = $cell->getRow();

                        // Value
                        $value = $cell->getValue();

                        if ($row >= 2) {
                            if ($emptyRow && $value != NULL && $value != '') {
                                $emptyRow = false;
                            }

                            if ($col == 'A') {
                                // group name
                                $pointOfSale->setGroupName($value);
                            }
                            else if ($col == 'B') {
                                // Business name
                                $pointOfSale->setBusinessName($value);
                            }
                            else if ($col == 'C') {
                                // Nit
                                // Look the sale channel name
                                $nit = $em->getRepository('AppBundle:PointOfSale')->findOneByTaxIdentifier($value);

                                if (!$nit) {
                                    $pointOfSale->setTaxIdentifier($value);
                                }
                                else {
                                    // No sale channel
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                        ->createPointOfSaleLog($user, 'El NIT existe en la base de datos.');
                                    break;
                                }
                            }
                            else if ($col == 'D') {
                                // Point of sale type
                                // Look the point sale type name
                                $pointOfSaleType = $em->getRepository('AppBundle:PointOfSaleType')->findOneByName($value);

                                if ($pointOfSaleType) {
                                    $pointOfSale->setPointOfSaleType($pointOfSaleType);
                                }
                                else {
                                    // No type found
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                        ->createPointOfSaleLog($user, 'No se encontr&oacute; el tipo del establecimiento');
                                    break;
                                }
                            }
                            else if ($col == 'E') {
                                $pointOfSale->setAddress1($value);
                            }
                            else if ($col == 'F') {
                                $pointOfSale->setAddress2($value);
                            }
                            else if ($col == 'G') {
                                $pointOfSale->setHomePhone($value);
                            }
                            else if ($col == 'H') {
                                $pointOfSale->setVersion($value);
                            }
                            else if ($col == 'I') {
                                // Check that is unique
                                $pointOfSaleDb = $em->getRepository('AppBundle:PointOfSale')->findOneByPointOfSaleInnerId($value);
                                
                                if ($pointOfSaleDb) {
                                    // Create error
                                    $errors[$row] = $em->getRepository('AppBundle:LogParser')
                                    ->createPointOfSaleLog($user, 'El C&oacute;digo interno del establecimiento ya existe');
                                    break;
                                }
                                $pointOfSale->setPointOfSaleInnerId($value);
                            }
                                                           
                        }
                    }
                }

                // If all cells in row (before error) were empty, delete found error
                if ($emptyRow) {
                    unset($errors[$row]);
                }

                // After each row add to point of sale to point of sales
                if ($pointOfSale->isValid()) {
                    array_push($pointOfSales, $pointOfSale);
                }
            }
        }

        if (sizeof($errors) > 0) {
            return $errors;
        }

        return $pointOfSales;
    }

    /**
     * Validates the information of a editing point of sale
     *
     * @param PointOfSale $pointOfSale pointOfSale to edit
     * @param string $oldTaxIdentifier the tax identifier before edit
     * @param string $oldPointOfSaleInnerId the point of sale inner id before edit
     * @return string error message. NULL if no error
     */
    public function validateEdit($pointOfSale, $oldTaxIdentifier, $oldPointOfSaleInnerId) {
        $em = $this->getDoctrine()->getManager();

        // Needs tax identifier
        if ($pointOfSale->getTaxIdentifier() === NULL) {
            return "Debe ingresar Nit";
        }

        
        // Has to have id
        if ($pointOfSale->getPointOfSaleInnerId() === NULL) {
            return "Debe ingresar codigo interno del establecimiento";
        }

        // Email has to be a regex email
        if ($pointOfSale->getEmail() !== NULL
            && $pointOfSale->getEmail() != 0
            && (!filter_var($pointOfSale->getEmail(), FILTER_VALIDATE_EMAIL))
            ) {
            return 'Su correo es inválido';
        }

        // Point of sale inner id has to be unique
        if ($pointOfSale->getPointOfSaleInnerId() != $oldPointOfSaleInnerId) {
            $duplicate = $em->getRepository('AppBundle:PointOfSale')
                ->findOneByPointOfSaleInnerId($pointOfSale->getPointOfSaleInnerId());

            if ($duplicate) {
                return "El codigo interno del establecimiento ya existe";
            }
        }

        return null;
    }

    /**
     * Makes some changes to entity and updates it in db
     *
     * @param PointOfSale $pointOfSale pointOfSale to update
     * @return PointOfSale updated entity
     */
    public function edit($pointOfSale){
        $em = $this->getDoctrine()->getManager();

        // Store in DB
        $em->persist($pointOfSale);
        $em->flush();

        return $pointOfSale;
    }

    /**
     * Create a point of sale
     *
     * @param PointOfSale $pointOfSale pointOfSale to update
     * @return PointOfSale created entity
     */
    public function create($pointOfSale){
        $em = $this->getDoctrine()->getManager();

        // Set created at
        $pointOfSale->setCreatedAt(new \DateTime());

        // Set created by
        $pointOfSale->setCreatedBy($this->getUser()->getId());

        // Set country
        $pointOfSale->setCountry($em->getReference('AppBundle\Entity\Country', 1));

        // Store in DB
        $em->persist($pointOfSale);
        $em->flush();

        return $pointOfSale;
    }
}
