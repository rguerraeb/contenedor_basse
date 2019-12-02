<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Staff;
use AppBundle\Entity\Prize;
use AppBundle\Form\PrizeType;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Repository\EbClosion;

class PrizeExchangeController extends Controller
{
    /**
     * @Route(
     *  "/backend/prize-exchange/staff/{id}",
     *  name="backend_staff_prize_exchange_staff",
     *  requirements={"id": "\d+"}
     * )
     */
    public function staffAction(Request $request, Staff $staff)
    {
        // Get parameters for serach
        $createdAt = $request->get('createdAt');
        $prize = $request->get('prize');
        $points = $request->get('points');
        $phone = $request->get('phone');

        $em = $this->getDoctrine()->getManager();
        $prizeExchanges = $em->getRepository('AppBundle:PrizeExchange')->getByStaff(
            $createdAt, $prize, $points, $phone, $staff
        );

        // Transform to remove objects
        $prizeExchangesAr = array();
        foreach ($prizeExchanges as $prizeExchange) {
            $prizeExchangesAr[] = array(
                $prizeExchange['id'],
                $prizeExchange['createdAt']->format('Y-m-d H:i'),
                $prizeExchange['prize'],
                $prizeExchange['points'],
                $prizeExchange['phone'],
            );
        }

        return new JsonResponse(array(
            'status' => 200,
            'data' => $prizeExchangesAr
        ));
    }
    
    
    
     /**
     * @Route(
     *  "/backend/prize",
     *  name="backend_prize",
     * )
     */
    public function backend_prizeAction(Request $request)
    {
	    $this->moduleId = 32;
      	$this->get("session")->set("module_id", $this->moduleId);
    	
        $em = $this->getDoctrine()->getManager();

        $prize = new Prize();
        $form = $this->createForm (new PrizeType (), $prize);

        $form->handleRequest ( $request );

        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {                               
                
                $error = $this->validateEdit($prize, NULL);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {

	    			$file = $prize->getImagePath();
					
	    			// Generate a unique name for the file before saving it
	    			$fileName = md5(uniqid()).'.'.$file->guessExtension();
	                
	                $file->move(
	    				$this->getParameter('prize_image_path'),
	    				$fileName
	    			);
	    			
	    			
					$prize->setImagePath($fileName);

                    $prize = $this->create($prize);
                    $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                }
            } else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }
        $prizes = $em->getRepository ( 'AppBundle:Prize')->findBy(array("editable" => 1));
               
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));

        return $this->render ( '@App/Backend/Prize/index.html.twig', array(
                "prizes" => $prizes,
                "form" => $form->createView (),
                "permits" => $mp
        ) );
    
    }
    
    
     /**
     * Validates the information of a editing prize
     *
     * @param Prize $prize prize to edit
     * @param string $oldPrizeFilterString the prize filter string of the old entity
     * @return string error message. NULL if no error
     */
    public function validateEdit($prize, $oldPrizeFilterString) {
        $em = $this->getDoctrine()->getManager();

       

        return null;
    }
    
    
    /**
     * Makes some changes to entity and insert it in db
     *
     * @param Prize $prize prize to insert
     * @return Prize updated entity
     */
    public function create($prize){
        $em = $this->getDoctrine()->getManager();

        // Created at
        $prize->setCreatedAt(new \DateTime());
        // Created By
        $prize->setCreatedBy($this->getUser()->getId());
        
        $prize->setName($prize->getDisplayName());
        $prize->setEditable("1");
        $prize->setOrderNumber("10");
        $prize->setRedemptionPartner("GAMA");
        $prize->setPrizeType("GAMA");
        $prize->setTypeExchange("GAMA");

        // Store in DB
        $em->persist($prize);
        $em->flush();

        return $prize;
    }
    
    
    /**
     * @Route("/backend/prize/edit/{id}", name="backend_prize_edit", requirements={"id": "\d+"})
     */
    public function editAction(Request $request, Prize $prize) {
        if ($prize) {
            
            $editForm = $this->createForm ('AppBundle\Form\PrizeType', $prize);
			$oldImage = $prize->getImagePath();
			$editForm->handleRequest($request);
			
			$em = $this->getDoctrine ()->getManager ();
			$userData = $this->get ( "session" )->get ( "userData" );
			$user = $em->getRepository("AppBundle:User")->findOneBy(array("id" => $this->getUser()->getId()));


            if ($editForm->isSubmitted()){
	            if ($editForm->isValid()) {
	                $em = $this->getDoctrine()->getManager();
	
	                $file = $prize->getImagePath();
	
	                if ($file) {
	                    // Generate a unique name for the file before saving it
	                    $fileName = md5(uniqid()).'.'.$file->guessExtension();
	                    // Move the file to the directory where brochures are stored
	                    $file->move(
	                        $this->getParameter('prize_image_path'),
	                        $fileName
	                    );
	                    $prize->setImagePath($fileName);
	                }
	                else {
	                    $prize->setImagePath($oldImage);
	                }
	                
					$prize->setUpdatedAt(new \DateTime());
	                $prize->setUpdatedBy($user);
	                
	                $em = $this->getDoctrine()->getManager();
	                $em->persist($prize);
	                $em->flush();
	
	                $this->addFlash ( 'success_message', $this->getParameter ( 'exito' ) );
	                return $this->redirectToRoute('backend_prize');
	            }    
					
			}else {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        return $this->render('@App/Backend/Prize/edit.html.twig', array(
            'form' => $editForm->createView()
        ));

    }

    /**
     * @Route("/backend/prize/delete/{id}", name="backend_prize_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, Prize $prize) {
        if ($prize) {
            // Try to delete
            try {
                $em = $this->getDoctrine ()->getManager ();

                $em->remove( $prize );
                $em->flush();

                $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
            }
            catch (\Exception $e) {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
            }
        } else {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }
        
        return $this->redirectToRoute ( "backend_prize" );
    }



    
}
