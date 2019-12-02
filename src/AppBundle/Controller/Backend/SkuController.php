<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SkuType;
use AppBundle\Entity\Sku;
use AppBundle\Repository\EbClosion;

class SkuController extends Controller
{
    
    private $moduleId = 8;
    
    /**
     * @Route("/backend/sku", name="backend_sku")
     */
    public function indexAction(Request $request)
    {
        
    	$this->get("session")->set("module_id", $this->moduleId);
    	
        $em = $this->getDoctrine()->getManager();

        $sku = new Sku();
        $form = $this->createForm (new SkuType (), $sku);

        $form->handleRequest ( $request );

        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {                               
                
                $error = $this->validateEdit($sku, NULL);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {
                    $sku = $this->create($sku);
                    $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                }
            } else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        $skus = $em->getRepository ( 'AppBundle:Sku')->findAll();
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate (
            $skus,
            $request->query->getInt ('page', 1 ),
            $this->getParameter ("number_of_rows")
        );
        
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/Sku/index.html.twig', array(
                "skus" => $pagination,
                "form" => $form->createView (),
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/sku/edit/{id}", name="backend_sku_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, Sku $sku) {
        if ($sku) {
            $form = $this->createForm (new SkuType (), $sku);

            // Old variables
            $oldSkuFilterString = $sku->getSkuFilterString();

            $form->handleRequest ( $request );

            if ($form->isSubmitted ()) {
                if ($form->isValid ()) {
                    $error = $this->validateEdit($sku, $oldSkuFilterString);

                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        $sku = $this->edit($sku);
                        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                    }
                } else {
                    // Error validation
                    $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
                }
            }
            return $this->render ( '@App/Backend/Sku/edit.html.twig', array(
                    "form" => $form->createView ()
            ) );
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }
        return $this->redirectToRoute ( "backend_sku" );
    }

    /**
     * @Route("/backend/sku/delete/{id}", name="backend_sku_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, Sku $sku) {
        if ($sku) {
            // Try to delete
            try {
                $em = $this->getDoctrine ()->getManager ();

                $em->remove( $sku );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }
        
        return $this->redirectToRoute ( "backend_sku" );
    }

    /**
     * Validates the information of a editing sku
     *
     * @param Sku $sku sku to edit
     * @param string $oldSkuFilterString the sku filter string of the old entity
     * @return string error message. NULL if no error
     */
    public function validateEdit($sku, $oldSkuFilterString) {
        $em = $this->getDoctrine()->getManager();

        // Sku filter string has to be unique
        if ($sku->getSkuFilterString() != $oldSkuFilterString) {
            $duplicate = $em->getRepository('AppBundle:Sku')
                ->findOneBySkuFilterString($sku->getSkuFilterString());

            if ($duplicate) {
                return "Ya existe el material";
            }
        }

        // Year has to have a valid format
        /*
        $year = $sku->getYear();
        $d = \DateTime::createFromFormat('Y', $year);
        if (! ($d && $d->format('Y') === $year)) {
            // Invalid date
            return 'Fecha inválida';
        }
        */

        return null;
    }

    /**
     * Makes some changes to entity and updates it in db
     *
     * @param Sku $sku sku to update
     * @return Sku updated entity
     */
    public function edit($sku){
        $em = $this->getDoctrine()->getManager();

        // Store in DB
        $em->persist($sku);
        $em->flush();

        return $sku;
    }

    /**
     * Makes some changes to entity and insert it in db
     *
     * @param Sku $sku sku to insert
     * @return Sku updated entity
     */
    public function create($sku){
        $em = $this->getDoctrine()->getManager();

        // Created at
        $sku->setCreatedAt(new \DateTime());

        // Created By
        $sku->setCreatedBy($this->getUser()->getId());

        // Store in DB
        $em->persist($sku);
        $em->flush();

        return $sku;
    }
}