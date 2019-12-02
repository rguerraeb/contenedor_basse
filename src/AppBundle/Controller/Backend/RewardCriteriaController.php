<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\RewardCriteriaType;
use AppBundle\Entity\RewardCriteria;
use AppBundle\Repository\EbClosion;

class RewardCriteriaController extends Controller
{
    
    private $moduleId = 9;
    
    /**
     * @Route("/backend/reward-criteria", name="backend_reward_criteria")
     */
    public function indexAction(Request $request)
    {
    	
        $this->get("session")->set("module_id", $this->moduleId);
    	
        $em = $this->getDoctrine()->getManager();

        $rewardCriteria = new RewardCriteria();
        $form = $this->createForm (new RewardCriteriaType (), $rewardCriteria);

        $form->handleRequest ( $request );

        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                $error = $this->validateEdit($rewardCriteria);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {
                    $user = $this->create($rewardCriteria);
                    $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                }
            } else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        $rewardCriterias = $em->getRepository ( 'AppBundle:RewardCriteria')->findAll();
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate (
            $rewardCriterias,
            $request->query->getInt ('page', 1 ),
            $this->getParameter ("number_of_rows")
        );
        
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/RewardCriteria/index.html.twig', array(
                "rewardCriterias" => $pagination,
                "form" => $form->createView (),
                "permits" => $mp
        ) );
    }

    /**
     * @Route("/backend/reward-criteria/edit/{id}", name="backend_reward_criteria_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, RewardCriteria $rewardCriteria) {
        if ($rewardCriteria) {
            $form = $this->createForm (new RewardCriteriaType (), $rewardCriteria);

            $form->handleRequest ( $request );

            if ($form->isSubmitted ()) {
                if ($form->isValid ()) {
                    $error = $this->validateEdit($rewardCriteria);

                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        $user = $this->edit($rewardCriteria);
                        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                    }
                } else {
                    // Error validation
                    $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
                }
            }
            return $this->render ( '@App/Backend/RewardCriteria/edit.html.twig', array(
                    "form" => $form->createView ()
            ) );
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_editar' ) );
        }
        return $this->redirectToRoute ( "backend_reward_criteria" );
    }

    /**
     * @Route("/backend/reward-criteria/delete/{id}", name="backend_reward_criteria_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, RewardCriteria $rewardCriteria) {
        if ($rewardCriteria) {
            // Try to delete
            try {
                $em = $this->getDoctrine ()->getManager ();

                $em->remove( $rewardCriteria );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }
        
        return $this->redirectToRoute ( "backend_reward_criteria" );
    }

    /**
     * Validates the information of a editing rewardCriteria
     *
     * @param RewardCriteria $rewardCriteria rewardCriteria to edit
     * @return string error message. NULL if no error
     */
    public function validateEdit($rewardCriteria) {
        $em = $this->getDoctrine()->getManager();

        // Mathematical operator has to be number
        if (! is_numeric($rewardCriteria->getMathematicalOperator())) {
            return 'Operador matemático incorrecto';
        }

        return null;
    }

    /**
     * Makes some changes to entity and updates it in db
     *
     * @param RewardCriteria $rewardCriteria rewardCriteria to update
     * @return RewardCriteria updated entity
     */
    public function edit($rewardCriteria){
        $em = $this->getDoctrine()->getManager();

        // Store in DB
        $em->persist($rewardCriteria);
        $em->flush();

        return $rewardCriteria;
    }

    /**
     * Makes some changes to entity and insert it in db
     *
     * @param RewardCriteria $rewardCriteria rewardCriteria to insert
     * @return RewardCriteria updated entity
     */
    public function create($rewardCriteria){
        $em = $this->getDoctrine()->getManager();

        // Created at
        $rewardCriteria->setCreatedAt(new \DateTime());

        // Created By
        $rewardCriteria->setCreatedBy($this->getUser()->getId());

        // Store in DB
        $em->persist($rewardCriteria);
        $em->flush();

        return $rewardCriteria;
    }
}