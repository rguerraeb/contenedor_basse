<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Goal;
use AppBundle\Entity\ParserRegister;
use AppBundle\Entity\StaffGoal;
use AppBundle\Form\GoalType;
use AppBundle\Form\GoalFileType;
use AppBundle\Repository\EbClosion;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Helper\SessionHelper;

class GoalController extends Controller {
    private $moduleId = 25;

    /**
     * @Route("/backend/goal", name="backend_goal")
     */
    public function indexAction(Request $request) {
        // Set in session to show as active
        $this->get("session")->set("module_id", $this->moduleId);

        // Create form goal
        $goal = new Goal ();
        $form = $this->createForm ( new GoalType (), $goal);
        $form->handleRequest ( $request );
        $em = $this->getDoctrine()->getManager();

        // Validar formulario
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                // Trim name and description
                $goal->setName(trim($goal->getName()));
                $goal->setDescription(trim($goal->getDescription()));

                // Job Positions needed for validations
                $jobPositions = $form['jobPosition']->getData();

                // Check for errors
                $error = $this->validate($goal, $jobPositions);

                if ($error) {
                    // Add error flash
                    $this->addFlash('error_message', $error);
                }
                else {
                    // Use months to calculate average
                    $months = $request->get('months');
                    $average = $em->getRepository('AppBundle:Goal')
                        ->average($months, $this->jobPositionsToIds($jobPositions));

                    // Check for errors
                    $error = $this->validateCreate($goal, $jobPositions);
                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        $goal->setQuantityByCriteria($average, $goal->getQuantity(), $jobPositions);

                        $goal = $this->create($goal);

                        // Set all relations
                        $this->createRelations($goal, $form);

                        $this->addFlash (
                            'success_message',
                            'Se guardó su meta con el objetivo = ' . $goal->getQuantity()
                        );

                        // Create form goal
                        $goal = new Goal ();
                        $form = $this->createForm ( new GoalType (), $goal);
                    }
                }
            }
            else {
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        // Get search parameters
        $name = $request->get('name');
        $type = $request->get('type');
        $jobPosition = $request->get('jobPosition');

        $goals = $em->getRepository ( 'AppBundle:Goal' )->search ($name, $type, $jobPosition);
        $paginator = $this->get ( 'knp_paginator' );
        
        $pagination = $paginator->paginate (
            $goals,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows" )
        );

        // Permission for actions
        $mp = EbClosion::getModulePermission($this->moduleId, $this->get("session")->get("userModules"));
        $types = $em->getRepository('AppBundle:GoalType')->findAll();
        $jobPositions = $em->getRepository('AppBundle:JobPosition')
            ->findByNot(
                array(
                    array(
                        'field' => 'id',
                        'value' => 1
                    ),
                    array(
                        'field' => 'id',
                        'value' => 3
                    )
                )
            );

        return $this->render ( '@App/Backend/Goal/index.html.twig',
            array(
                "form" => $form->createView (),
                "goals" => $pagination,
                "permits" => $mp,
                'types' => $types,
                'jobPositions' => $jobPositions
            )
        );
    }
    
    /**
     * @Route(
     *  "/backend/goal/edit/{id}",
     *  name="backend_goal_edit",
     *  requirements={"id": "\d+"}
     * )
     */
    public function editAction(Request $request, Goal $goal) {
        // Check if eliminated
        if ($goal->getGoalStatus()->getId() == 3) {
            throw $this->createNotFoundException('No existe la meta');
        }

        $em = $this->getDoctrine()->getManager();
        // Get selected values
        $pointsOfSale = $em->getRepository('AppBundle:GoalPointOfSale')
            ->getPointOfSaleByGoal($goal);
        $states = $em->getRepository('AppBundle:GoalState')
            ->getStateByGoal($goal);
        $cities = $em->getRepository('AppBundle:GoalCity')
            ->getCityByGoal($goal);
        $saleChannels = $em->getRepository('AppBundle:GoalSaleChannel')
            ->getSaleChannelByGoal($goal);
        $jobPositions = $em->getRepository('AppBundle:GoalJobPosition')
            ->getJobPositionByGoal($goal);

        // Old goal
        $oldGoal = clone $goal;

        $form = $this->createForm (
            new GoalType (),
            $goal,
            array(
                'pointsOfSale' => $pointsOfSale,
                'cities' => $cities,
                'saleChannels' => $saleChannels,
                'jobPositions' => $jobPositions,
                'states' => $states
            )
        );
        $form->handleRequest ( $request );

        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted ()) {
            if ($form->isValid ()) {
                // Make sure dates stay the same
                $goal->setStart($oldGoal->getStart());
                $goal->setEnd($oldGoal->getEnd());

                // Job Positions needed for validations
                $jobPositions = $form['jobPosition']->getData();

                $error = $this->validate($goal, $jobPositions);

                if ($error) {
                    $this->addFlash('error_message', $error);
                }
                else {
                    $error = $this->validateEdit($goal, $jobPositions);
                    if ($error) {
                        $this->addFlash('error_message', $error);
                    }
                    else {
                        // Use months to calculate average
                        $months = $request->get('months');
                        $average = $em->getRepository('AppBundle:Goal')
                            ->average($months, $jobPositions);

                        $goal->setQuantityByCriteria($average, $goal->getQuantity(), $jobPositions);

                        $goal = $this->edit($goal);

                        // Set all relations
                        $this->createRelations($goal, $form);

                        $this->addFlash (
                            'success_message',
                            'Se actualizó la meta con objetivo = ' . $goal->getQuantity()
                        );
                    }
                }
            }
            else {
                // Error validation
                $this->addFlash ( 'error_message', $this->getParameter ( 'error_form' ) );
            }
        }

        return $this->render ( '@App/Backend/Goal/edit.html.twig', array(
                "form" => $form->createView (),
                "edit" => true
        ) );
    }
    
    /**
     * @Route(
     *  "/backend/goal/delete/{id}",
     *  name="backend_goal_delete",
     *  requirements={"id": "\d+"}
     * )
     */
    public function deleteAction(Request $request, Goal $goal) {
        $em = $this->getDoctrine()->getManager();

        // Try to delete
        try {
            // Make eliminated
            $goal->setGoalStatus($em->getReference('AppBundle:GoalStatus', 3));
            $em->persist( $goal );
            $em->flush ();
            $this->addFlash ( 'success_message', $this->getParameter ( 'exito_eliminar' ) );
        }
        catch (\Exception $e) {
            $this->addFlash ( 'error_message', $this->getParameter ( 'error_eliminar' ) );
        }

        return $this->redirectToRoute ( "backend_goal" );
    }

    /**
     * @Route(
     *  "/backend/goal/view/{id}",
     *  name="backend_goal_view",
     *  requirements={"id": "\d+"}
     * )
     */
    public function viewAction(Request $request, Goal $goal) {
        // Check if eliminated
        if ($goal->getGoalStatus()->getId() == 3) {
            throw $this->createNotFoundException('No existe la meta');
        }

        $em = $this->getDoctrine()->getManager();

        // Set get parameters
        $name = $request->get('name');
        $citizenId = $request->get('citizen_id');
        $phone = $request->get('phone');

        $staffs = $em->getRepository('AppBundle:Staff')
            ->goalSearch($name, $citizenId, $phone, $goal);

        $pointsOfSale = $em->getRepository('AppBundle:GoalPointOfSale')
            ->getPointOfSaleByGoal($goal);
        $states = $em->getRepository('AppBundle:GoalState')
            ->getStateByGoal($goal);
        $cities = $em->getRepository('AppBundle:GoalCity')
            ->getCityByGoal($goal);
        $saleChannels = $em->getRepository('AppBundle:GoalSaleChannel')
            ->getSaleChannelByGoal($goal);
        $jobPositions = $em->getRepository('AppBundle:GoalJobPosition')
            ->getJobPositionByGoal($goal);

        // Paginate
        $paginator = $this->get ( 'knp_paginator' );

        $pagination = $paginator->paginate (
            $staffs,
            $request->query->getInt ( 'page', 1 ),
            $this->getParameter ( "number_of_rows" )
        );

        return $this->render ( '@App/Backend/Goal/view.html.twig', array(
                "goal" => $goal,
                "staffs" => $pagination,
                'pointsOfSale' => $pointsOfSale,
                'states' => $states,
                'cities' => $cities,
                'saleChannels' => $saleChannels,
                'jobPositions' => $jobPositions,
        ));
        return $this->redirectToRoute ( "backend_goal" );
    }

    /**
     * @Route(
     *  "/backend/goal/average",
     *  name="backend_goal_average"
     * )
     */
    public function averageAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        // Get parameters
        $months = $request->get('months');
        $jobPositionIds = $request->get('jobPositionIds');
        
        // This values needs to be here
        if (!$months) {
            return new JsonResponse(array(
            'status' => 200,
            'average' => 0
        ));
        }

        $average = $em->getRepository('AppBundle:Goal')->average($months, $jobPositionIds);

        return new JsonResponse(array(
            'status' => 200,
            'average' => $average
        ));
    }

    public function activeAction(Request $request) {
        if(!$request->isXmlHttpRequest()) {
            return $this->redirect($this->generateUrl('backend_goal'));
        }

        $em = $this->getDoctrine()->getManager();
        // Look for an active goal
        $goal = $em->getRepository('AppBundle:Goal')
            ->findOneByGoalStatus(
                $em->getReference('AppBundle:GoalStatus', 1)
            );

        if ($goal) {
            return new JsonResponse(array(
                'status' => 200,
                'goal' => array(
                    'name' => $goal->getName(),
                    'id' => $goal->getId()
                )
            ));
        }

        return new JsonResponse(array(
            'status' => 404,
            'message' => 'No se encontró alguna meta activa'
        ));
    }

    /**
     * Encodes password, sets created at and by and stores in db
     * 
     * @param Goal $goal the entity to be created
     * @return Goal user saved in db
     */
    public function create($goal) {
        $em = $this->getDoctrine()->getManager();

        // Set created At
        $goal->setCreatedAt(new \DateTime());

        // Change dates to make them inclusive
        $startDate = $goal->getStart();
        $startDate->setTime(0, 0, 0);
        $endDate = $goal->getEnd();
        $endDate->setTime(23, 59, 59);
        $goal->setStart($startDate);
        $goal->setEnd($endDate);

        $goal->setCorrectPrize();

        // Save in database
        $em->persist($goal);
        $em->flush();

        return $goal;
    }

    /**
     * Validates the information of goal
     *
     * @param Goal $goal changing goal
     * @param array jobPositions assigned to goal
     * @return string error message. NULL if no error
     */
    public function validate($goal, $jobPositions) {
        $em = $this->getDoctrine()->getManager();

        // Has to have name
        if (!$goal->getName() || $goal->getName() == '') {
            return 'Debe tener nombre';
        }

        // Can't create with status 'Eliminado'
        if (!$goal->getGoalStatus() || $goal->getGoalStatus()->getId() == 3) {
            return 'Solo puede crear una meta como "Activa" o "Inactiva"';
        }

        // Has to have start and end date
        if (!$goal->getStart() || !$goal->getEnd()) {
            return 'Debe tener fecha de inicio y fin';
        }

        // Criteria for goal
        if (!$goal->getQuantity() || $goal->getQuantity() <= 0) {
            return 'No se puede ingresar un valor negativo como meta';
        }

        if (!$jobPositions || sizeof($jobPositions) == 0) {
            return 'Debe elegir algún puesto';
        }

        if (sizeof($jobPositions) > 0) {
            // Can't have job positions for both types of goal
            $unitJps = $goal->getUnitJps();
            $percentageJps = $goal->getPercentageJps();
            $jpIds = $this->jobPositionsToIds($jobPositions);

            if (array_intersect($unitJps, $jpIds) && array_intersect($percentageJps, $jpIds)) {
                // If both intersect, there is an error
                return 'No se puede combinar los puestos que usen meta por unidad y meta por porcentaje';
            }
        }

        return null;
    }

    /**
     * Validates the information of a new goal
     *
     * @param Goal $goal possible new goal
     * @param array $jobPositions jobPositions assigned to goal
     * @return string error message. NULL if no error
     */
    public function validateCreate($goal, $jobPositions) {
        $em = $this->getDoctrine()->getManager();

        // Start date has to be greater or equal than today
        $today = new \DateTime('today');
        if ($goal->getStart() < $today) {
            return 'La fecha de inicio debe ser hoy o después';
        }

        // End date hast to be greater or equal than today
        if ($goal->getEnd() < $today) {
            return 'La fecha de fin debe ser hoy o después';
        }

        // Goal end has to be after start
        if ($goal->getEnd() < $goal->getStart()) {
            return 'La fecha de fin no puede ser antes que la fecha de inicio';
        }

        if ($goal->getGoalStatus()->getId() == 1) {
            // Check that goal's dates don't intersect with other goals
            $intersects = $em->getRepository('AppBundle:Goal')
                ->intersects(
                    $goal->getStart(),
                    $goal->getEnd(),
                    $jobPositions,
                    NULL
                );

            if (sizeof($intersects) > 0) {
                return 'La meta intersecta con alguna otra meta';
            }
        }

        return null;
    }

    /**
     * Validates goal being edited
     *
     * @param Goal $goal edited goal
     * @param array $jobPositions jobPositions assigned to goal
     * @return string error description
     */
    public function validateEdit($goal, $jobPositions) {
        $em = $this->getDoctrine()->getManager();

        if ($goal->getGoalStatus()->getId() == 1) {
            // Check that goal's dates don't intersect with other goals
            $intersects = $em->getRepository('AppBundle:Goal')
                ->intersects(
                    $goal->getStart(),
                    $goal->getEnd(),
                    $jobPositions,
                    $goal
                );

            if (sizeof($intersects) > 0) {
                return 'La meta intersecta con alguna otra meta';
            }
        }

        return NULL;
    }

    /**
     * Updates a goal in database
     *
     * @param Goal $goal goal to update
     * @param Goal $oldGoal "stored" goal
     * @return Goal edited goal
     */
    public function edit($goal){
        $em = $this->getDoctrine()->getManager();

        $goal->setCorrectPrize();

        // Store in DB
        $em->persist($goal);
        $em->flush();

        return $goal;
    }

    /**
     * From array of job positions gets their ids and returns them in an array
     *
     * @param array JobPositions to get their ids
     * @return arary ids of jobPositions
     */
    private function jobPositionsToIds($jobPositions) {
        $ids = array();
        foreach ($jobPositions as $jobPosition) {
            array_push($ids, $jobPosition->getId());
        }

        return $ids;
    }

    /**
     * Creates and deletes relations the goal may have
     *
     * @param Goal $goal goal to use in relations
     * @param Form $form form with data for relations
     */
    private function createRelations($goal, $form) {
        $em = $this->getDoctrine()->getManager();

        // Get helper to use functions
        $entityHelper = $this->get('entity.helper');

        // Get original points of sale with this goal
        $pointsOfSale = $form['pointOfSale']->getData();
        // Add and delete to em, without flushing
        $em = $entityHelper->emPersistRemove(
            'Goal',
            $goal,
            $pointsOfSale,
            'PointOfSale',
            array(
                'getPointOfSaleId'
            ),
            array(
                'getPointOfSale',
                'getPointOfSaleId'
            ),
            true,
            $em
        );

        // Now for states
        $states = $form['state']->getData();
        $em = $entityHelper->emPersistRemove(
            'Goal',
            $goal,
            $states,
            'State',
            array(
                'getStateId'
            ),
            array(
                'getState',
                'getStateId'
            ),
            true,
            $em
        );

        // Now for cities
        $cities = $form['city']->getData();
        $em = $entityHelper->emPersistRemove(
            'Goal',
            $goal,
            $cities,
            'City',
            array(
                'getId'
            ),
            array(
                'getCity',
                'getId'
            ),
            true,
            $em
        );

        // Now for saleChannels
        $saleChannels = $form['saleChannel']->getData();
        $em = $entityHelper->emPersistRemove(
            'Goal',
            $goal,
            $saleChannels,
            'SaleChannel',
            array(
                'getSaleChannelId'
            ),
            array(
                'getSaleChannel',
                'getSaleChannelId'
            ),
            true,
            $em
        );

        // Now for jobPositions
        $jobPositions = $form['jobPosition']->getData();
        $em = $entityHelper->emPersistRemove(
            'Goal',
            $goal,
            $jobPositions,
            'JobPosition',
            array(
                'getId'
            ),
            array(
                'getJobPosition',
                'getId'
            ),
            true,
            $em
        );

        $em->flush();
    }
}
