<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Goal;
use AppBundle\Entity\StaffGoal;
use AppBundle\Form\StaffGoalType;

class StaffGoalController extends Controller {
    private $moduleId = 25;

    /**
     * @Route(
     *  "/backend/goal/edit/staff_goal/{id}",
     *  name="backend_goal_edit_staff_goal",
     *  requirements={"id": "\d+"}
     * )
     */
    public function editAction(Request $request, StaffGoal $staffGoal) {
        $form = $this->createForm ( new StaffGoalType (), $staffGoal );
        $form->handleRequest ( $request );
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                $error = $this->validate($staffGoal);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {
                    $staffGoal = $this->edit($staffGoal);

                    if ($staffGoal) {
                        $this->addFlash ( 'success_message', $this->getParameter ( 'exito_actualizar' ) );
                    }
                }

            } else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }
        return $this->render ( '@App/Backend/StaffGoal/edit.html.twig', array(
                "form" => $form->createView (),
                'goal' => $staffGoal->getGoal()
        ) );
    }

    /**
     * Validates the information of a new user
     *
     * @param StaffGoal $staffGoal staffGoal to update
     * @return string error message. NULL if no error
     */
    public function validate($staffGoal) {
        $em = $this->getDoctrine()->getManager();

        // Months value has to be an int > 0
        if (!is_int($staffGoal->getPoints()) || $staffGoal->getPoints() <= 0) {
            return 'La cantidad de puntos debe ser un número mayor a 0';
        }

        // Has to have percentage or quantity
        $staff = $staffGoal->getStaff();
        if ($staff->isQuantityGoal()) {
            if ($staffGoal->getQuantity() == NULL
                || $staffGoal->getQuantity() == ''
                || !is_numeric($staffGoal->getQuantity())
                || $staffGoal->getQuantity() <= 0) {
                return 'La cantidad de meses debe ser un entero mayor a 0';
            }
        }
        else {
            if ($staffGoal->getPercentage() == NULL
                || $staffGoal->getPercentage() == ''
                || !is_numeric($staffGoal->getPercentage())
                || $staffGoal->getPercentage() < 0) {
                return 'El porcentaje debe ser un número mayor a 0';
            }
        }

        return null;
    }

    /**
     * Updates entity on database3
     *
     * @param StaffGoal $staffGoal StaffGoal to update in database
     * @return StaffGoal updated $staffGoal
     */
    public function edit($staffGoal){
        $em = $this->getDoctrine()->getManager();

        try {
            // Set other criteria as null
            $staff = $staffGoal->getStaff();
            if ($staff->isQuantityGoal()) {
                $staffGoal->setPercentage(NULL);

                // Force int
                $staffGoal->setQuantity((int) $staffGoal->getQuantity());
            }
            else {
                $staffGoal->setQuantity(NULL);

                // Transform if necessary
                $percentage = $staffGoal->getPercentage();
            }

            // Store in DB
            $em->persist($staffGoal);
            $em->flush();

            return $staffGoal;
        } catch (\Exception $e) {
        }

        return NULL;
    }
}
