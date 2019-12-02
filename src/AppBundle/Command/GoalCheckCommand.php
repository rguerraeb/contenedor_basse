<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Entity\StaffPromoPoints;
use AppBundle\Entity\StaffGoal;
use AppBundle\Entity\StaffGoalNotification;
use AppBundle\Helper\SessionHelper;

class GoalCheckCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('goal:check')

            // the short description shown while running "php app/console list"
            ->setDescription('Checks how staffs are doing with their goals to add points')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Checks for goal in database. Checks quantity of goal to see if staff earned points or not")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Start output
        $output->writeln(array(
            'Goal Check ',
            '============',
            ''
        ));

        // Look for active goal
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $output->writeln('Looking for active goals...');

        $now = new \DateTime();
        $goals = $em->getRepository('AppBundle:Goal')
            ->intersects(
                $now, $now, NULL, NULL
            );

        if (sizeof($goals) <= 0) {
            $output->writeln('No active goal found');
            return;
        }

        $contInserts = 0;
        foreach ($goals as $goal) {
            $output->writeln(
                array(
                    '<fg=yellow>||------------------||</>',
                    'Checking goal No.' . $goal->getId()
                )
            );

            // Goal variables
            $goalStart = $goal->getStart();
            $goalEnd = $goal->getEnd();
            $salesGoal = $goal->getQuantity();

            // Look for staffs for this goal
            $output->writeln('Looking for staffs...');
            $staffs = $em->getRepository('AppBundle:Staff')
                ->goalSearch(NULL, NULL, NULL, $goal);

            $output->writeln('Found ' . sizeof($staffs) . ' staffs');

            // Get notifications
            $notifications = $em->getRepository('AppBundle:Notification')
                ->findBy(
                    array(),
                    array(
                        'percentage' => 'DESC'
                    )
                );

            // Helper to send sms
            $helper = new SessionHelper();

            foreach ($staffs as $staff) {
                // Check if goal was already reached
                // Look if staff goal already exists
                $staffGoal = $em->getRepository('AppBundle:StaffGoal')
                    ->findOneBy(
                        array(
                            'staff' => $staff,
                            'goal' => $goal
                        )
                    );

                // If no staffGoal was found or the staffGoal found hasn't received his prize
                if (!$staffGoal) {
                    // Hasn't reached goal
                    // Calculate his sales to check if goal was reached
                    if ($staff->isSeller()) {
                        // Get total of sales
                        $contSales = $em->getRepository('AppBundle:Sale')
                            ->count(
                                $staff,
                                $goalStart->format('Y-m-d H:i:s'),
                                $goalEnd->format('Y-m-d H:i:s')
                            );
                    }
                    else {
                        $contSales = $em->getRepository('AppBundle:Sale')
                            ->countHierarchy(
                                $staff,
                                $goalStart->format('Y-m-d H:i:s'),
                                $goalEnd->format('Y-m-d H:i:s')
                            );
                    }

                    // Staff phone to send sms to
                    $phone = $staff->getAreaCode() . $staff->getPhoneMain();

                    if ($contSales >= $salesGoal) {
                        // Goal reached by staff
                        $output->writeln('Staff No. ' . $staff->getStaffId() . ' reached the goal');

                        // Send congratulations message
                        $gMessage = $this->getContainer()->getParameter('goal_reached');
                        $helper->send_sms_televida($phone, $gMessage);

                        $goalTypeId = $goal->getGoalType()->getGoalTypeId();

                        // No staff goal, need to create
                        $staffGoal = new StaffGoal();
                        $staffGoal->setStaff($staff);
                        $staffGoal->setGoal($goal);
                        $staffGoal->setCreatedAt(new \DateTime());
                        $staffGoal->setPhone($phone);

                        // Give prize
                        if ($goalTypeId == 1) {
                            $points = $goal->getPoints();

                            $output->writeln(array(
                                'Give points',
                                '    Points: "' . $points . '"'
                            ));

                            $giveInfo = $this->givePoints($staff, $points);

                            // Set staff goal attributes
                            $staffGoal->setPoints($points);
                            $staffGoal->setMessage(NULL);
                            $staffGoal->setPrize(NULL);

                            // Send sms with prize info
                            $gpMessage = $this->getGoalPointsMessage($points);
                            $helper->send_sms_televida($phone, $gpMessage);
                        }
                        else if ($goalTypeId == 2) {
                            $prize = $goal->getPrize();

                            $output->writeln(array(
                                'Exchange prize',
                                '    Prize: "' . $prize->getDisplayName() . '"',
                                '    Phone: "' . $phone . '"'
                            ));

                            // Give prize
                            $giveInfo = $this->givePrize($staff, $prize);

                            $code = $giveInfo['code'];

                            // Print code if one
                            if ($code) {
                                $output->writeln('    Code: "' . $code . '"');
                            }

                            $exchangeInfo = $giveInfo['exchangeInfo'];

                            // Send sms with prize info
                            $redeemHelper = $this->getApplication()->getKernel()
                                ->getContainer()->get('redeem.helper');
                            $redeemHelper->redeemSms(
                                $exchangeInfo, $staff, $prize, 'WEB', '');


                            // Set staffGoal variables
                            $staffGoal->setMessage(NULL);
                            $staffGoal->setPoints(NULL);
                            $staffGoal->setPrize($prize);
                        }
                        else if ($goalTypeId == 3) {
                            $message = $goal->getMessage();

                            $output->writeln(array(
                                'Send message',
                                '    Goal text: "' . $message . '"',
                                '    Receiver: "' . $phone . '"'
                            ));

                            // $giveInfo = $this->giveSms($phone, $message);
                            $message = $giveInfo['message'];

                            $output->writeln('    Message: "' . $message . '"');

                            // Set staffGoal variables
                            $staffGoal->setMessage($message);
                            $staffGoal->setPoints(NULL);
                            $staffGoal->setPrize(NULL);
                        }

                        $sgStatus = $giveInfo['status'];
                        if ($sgStatus != 1) {
                            $output->writeln('<error>Error while giving prize</error>');
                        }

                        // Update status of staff goal
                        $staffGoal->setStaffGoalStatus(
                            $em->getReference(
                                'AppBundle:StaffGoalStatus',
                                $sgStatus
                            )
                        );
                        $em->persist($staffGoal);
                        $em->flush();
                    }
                    else {
                        // Check for notifications
                        foreach ($notifications as $notification) {
                            $percentage = $notification->getPercentage();

                            // See if this staff already received this notification
                            $sgns = $em->getRepository('AppBundle:StaffGoalNotification')
                                ->findHigherPercentage(
                                    $notification->getPercentage(),
                                    $staff,
                                    $goal
                                );

                            if (!$sgns) {
                                // Hasn't received this notification
                                if ($contSales >= ($salesGoal * $percentage)) {
                                    // Percentage reached, send notification
                                    $output->writeln(
                                        'Staff No.' . $staff->getStaffId()
                                        . ' reached notification No.' .
                                        $notification->getNotificationId()
                                        . ' (' . $percentage . ')'
                                    );

                                    // Send sms
                                    $helper->send_sms_televida(
                                        $phone,
                                        $notification->getMessage()
                                    );

                                    // Store that the notification was sent
                                    $sgnObj = new StaffGoalNotification();
                                    $sgnObj->setStaff($staff);
                                    $sgnObj->setGoal($goal);
                                    $sgnObj->setNotification($notification);
                                    $sgnObj->setCreatedAt(new \DateTime());
                                    $em->persist($sgnObj);
                                    $em->flush();
                                }
                            }
                        }
                    }
                }
            }

            $output->writeln('');
        }
    }

    /**
     * Gives the SMS prize of goal
     *
     * @param string $phone phone to send sms to
     * @param string $prize prize of the goal
     * @return array(
     *  status => integer id of StaffGoalStatus, 1: if prize was delivered, 2: if prize wasn't delivered
     *  message => message sent
     * )
     */
    private function giveSms($phone, $prize) {
        $message = $this->getContainer()->getParameter('goal_message');
        $message = str_replace("[PRIZE]", $prize, $message);

        // Create helper to send SMS
        $helper = new SessionHelper();

        // Send sms
        $response = $helper->send_sms_televida($phone, $message);

        // Check response
        $responseObj = $helper->findJSONObject($response);
        if ($responseObj) {
            if (isset($responseObj->resultCode)) {
                // Read status
                if ($responseObj->resultCode == 0) {
                    // Success sending SMS
                    return array(
                        'status' => 1,
                        'message' => $message
                    );
                }
            }
        }

        return array(
            'status' => 2,
            'message' => $message
        );
    }

    /**
     * Gives the points to a staff
     *
     * @param Staff $staff staff to five points to
     * @param integer points $points to give staff
     * @return array(
     *  status => integer id of StaffGoalStatus, 1: if prize was delivered, 2: if prize wasn't delivered
     *  points => points given to staff
     * )
     */
    private function givePoints($staff, $points) {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Give points
        $spp = new StaffPromoPoints();
        $spp->setStaff($staff);
        $spp->setPromo($em->getReference('AppBundle:Promo', 3));
        $spp->setPoints($points);
        $spp->setCreatedAt(new \DateTime());
        $em->persist($spp);
        $em->flush();

        return array(
            'status' => 1,
            'points' => $points
        );
    }

    /**
     * Gives the prize to a staff
     *
     * @param Staff $staff staff to give the prize
     * @param Prize $prize prize to give
     * @return array(
     *  status => integer id of StaffGoalStatus, 1: if prize was delivered, 2: if prize wasn't delivered
     *  code => code if applicable,
     *  exchangeInfo => information returned by redeemHelper
     * )
     */
    private function givePrize($staff, $prize) {
        $redeemHelper = $this->getApplication()->getKernel()
            ->getContainer()->get('redeem.helper');

        $exchangeInfo = $redeemHelper->redeemDb($staff, $prize, 0, 'WEB');

        $code = NULL;
        if ($exchangeInfo['status'] == 200) {
            if (isset($exchangeInfo['code'])) {
                $code = $exchangeInfo['code'];
            }

            return array(
                'status' => 1,
                'code' => $code,
                'exchangeInfo' => $exchangeInfo
            );
        }

        return array(
            'status' => 2,
            'code' => $code,
            'exchangeInfo' => $exchangeInfo
        );
    }

    /**
     * Function that builds the string of message of the prize that was given
     *
     * @param string $prizeName name of prize to give
     * @return string built message
     */
    private function getGoalPointsMessage($points) {
        $messageBase = $this->getContainer()->getParameter('goal_points_message');

        // Get prize message in sms
        $message = str_replace("[POINTS]", $points, $messageBase);

        return $message;
    }
}