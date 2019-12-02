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

class PromoCheckCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('promo:check')

            // the short description shown while running "php app/console list"
            ->setDescription('Check which promos ended yesturday and gives prizes if necessary')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Just run 'promo:check'")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Start output
        $output->writeln(array(
            'Promo Check ',
            '============',
            ''
        ));

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Get yesturday interval
        $yesturday = new \DateTime();
        $yesturday->sub(new \DateInterval('P1D'));
        $entityHelper = $this->getApplication()->getKernel()->getContainer()->get('entity.helper');

        $start = $yesturday->format('Y-m-d 00:00:00');
        $end = $yesturday->format('Y-m-d 23:59:59');
        $output->writeln(
            'Looking for promos that ended between '
            . $start
            . ' and '
            . $end
        );

        $promos = $em->getRepository('AppBundle:Promo')
            ->findByEndDateBetween($start, $end);

        $total = sizeof($promos);
        $output->writeln('Found ' . $total . ' promo(s)');

        $cont = 1;
        foreach ($promos as $promo) {
            $output->writeln(array(
                '<fg=yellow>---- (' . $cont . '/' . $total . ') ----</>',
                'Checking promo:',
                '  id: ' . $promo->getPromoId(),
                '  name: ' . $promo->getName()
            ));

            if ($this->canGivePrizePromo($promo)) {
                // The command can give prize to this promo, get all filters
                $states = $em->getRepository('AppBundle:PromoState')
                    ->getStateByPromo($promo);
                $cities = $em->getRepository('AppBundle:PromoCity')
                    ->getCityByPromo($promo);
                $saleChannels = $em->getRepository('AppBundle:PromoSaleChannel')
                    ->getSaleChannelByPromo($promo);
                $pointOfSales = $em->getRepository('AppBundle:PromoPointOfSale')
                    ->getPointOfSaleByPromo($promo);
                $jobPositions = $em->getRepository('AppBundle:PromoJobPosition')
                    ->getJobPositionByPromo($promo);
                $skus = $em->getRepository('AppBundle:PromoCc')
                    ->getSkuByPromo($promo);

                // Get sales only in the time the promo was active
                $start = $promo->getStartDate()->format('Y-m-d H:i:s');
                $end = $promo->getEndDate()->format('Y-m-d H:i:s');

                // Find sales applying all found filters
                $output->writeln('Looking for sales using promo filters...');
                $sales = $em->getRepository('AppBundle:Sale')
                    ->findByMultiple($states, $cities, $saleChannels,
                        $pointOfSales, $jobPositions, $skus, $start, $end);
                $output->writeln('Found ' . sizeof($sales) . ' sale(s)');

                // Holds the amount of sales of each staff
                // array('{staffId}' => {totalSales})
                $staffSales = array();

                foreach ($sales as $sale) {
                    // Count each staff's sales
                    $staffId = $sale->getStaff()->getStaffId();

                    if (array_key_exists($staffId, $staffSales)) {
                        // Already in array, just add the sale
                        $staffSales[$staffId] = $staffSales[$staffId] + 1;
                    }
                    else {
                        $staffSales[$staffId] = 1;
                    }
                }

                // Get ids of the staffs with the most sales
                $winners = array();
                if (sizeof($staffSales) > 0) {
                    $winners = array_keys($staffSales, max($staffSales));
                }

                if (sizeof($winners) > 0) {
                    // Get prize to give
                    $prize = $em->getRepository('AppBundle:PromoPrize')
                        ->findOneByPromo($promo);

                    if ($prize) {
                        // Just use one winner
                        foreach ($winners as $winner) {
                            // Transform id of staff into staff
                            $staff = $em->getRepository('AppBundle:Staff')
                                ->findOneByStaffId($winner);
                            $phone = $staff->getAreaCode() . $staff->getPhoneMain();

                            // Send notification message that staff won a promo
                            $this->sendNotificationSms($staff, $prize, $promo);

                            $output->writeln(array(
                                'Give prize to staff: ' . $winner,
                                'Using prize:',
                                '  Type: ' . $prize->getPromoPrizeType()->getName()
                            ));

                            $prizeTypeId = $prize->getPromoPrizeType()->getPromoPrizeTypeId();
                            if ($prizeTypeId == 1) {
                                $points = $prize->getPoints();
                                $output->writeln('  Points: ' . $points);

                                $this->givePoints($staff, $promo, $prize->getPoints());

                                // Send sms with prize info
                                $helper = new SessionHelper();
                                $message = $this->getPrizePointsMessage($points);
                                $helper->send_sms_televida($phone, $message);
                            }
                            else if ($prizeTypeId == 2) {
                                $factor = $prize->getFactor();
                                $output->writeln('  Factor: ' . $factor);
                                $output->writeln('  PENDING');
                            }
                            else if ($prizeTypeId == 3) {
                                $specific = $prize->getName();
                                $output->writeln('  Specific: ' . $specific);

                                // Just send SMS with prize message
                                $status = $this->giveSms($phone, $specific, $promo->getName());

                                if ($status['status'] == 1) {
                                    $statusStr = 'Enviado';
                                }
                                else {
                                    $statusStr = 'Error al enviar mensaje';
                                }

                                $output->writeln(array(
                                    '  Message: ' . $status['message'],
                                    '  Status: ' . $statusStr
                                ));
                            }

                            // Just have one winner
                            break;
                        }
                    }
                }
            }

            $cont++;
        }
    }

    /**
     * Check if prize can be given to a promo by this command
     * 
     * @param Promo $promo promo to check if it can receive prize by this command
     * @return boolean if it can give prize to promo
     */
    public function canGivePrizePromo($promo) {
        if ($promo->getPromoCategory()) {
            $catId = $promo->getPromoCategory()->getPromoCategoryId();

            if ($catId == 2 || $catId == 3 || $catId == 4 || $catId == 5) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gives the SMS prize of goal
     *
     * @param string $phone phone to send sms to
     * @param string $specific string to go in prize
     * @param string $promo name of the promo to include in message
     * @return array(
     *  status => 1: message sent, 0: message not sent
     *  message => message sent
     * )
     */
    private function giveSms($phone, $specific, $promo) {
        $message = $this->getSpecificPrizeMessage($specific, $promo);

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
            'status' => 0,
            'message' => $message
        );
    }

    /**
     * Gives the points to a staff
     *
     * @param Staff $staff staff to five points to
     * @param Promo $promo promo to make relation in database
     * @param integer points $points to give staff
     */
    private function givePoints($staff, $promo, $points) {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        // Give points
        $spp = new StaffPromoPoints();
        $spp->setStaff($staff);
        $spp->setPromo($promo);
        $spp->setPoints($points);
        $spp->setCreatedAt(new \DateTime());
        $em->persist($spp);
        $em->flush();
    }

    /**
     * Send a SMS to staff notifying that he won a prize by a promo
     *
     * @param Staff $staff user to notify
     * @param PromoPrize $prize prize that is going to be given, to check if notification is required
     * @param Promo $promo promo that the staff won
     */
    private function sendNotificationSms($staff, $prize, $promo) {
        $prizeTypeId = $prize->getPromoPrizeType()->getPromoPrizeTypeId();

        if ($prizeTypeId == 1 || $prizeTypeId == 3) {
            // Create helper to send SMS
            $helper = new SessionHelper();

            $phone = $staff->getAreaCode() . $staff->getPhoneMain();
            $message = $this->getPromoWonMessage($promo);

            // Send sms
            $response = $helper->send_sms_televida($phone, $message);
        }
    }

    /**
     * Function that builds the string of message to use when user wins a promo
     *
     * @param Promo $promo promo that user won
     * @return string built message
     */
    private function getPromoWonMessage($promo) {
        $message = $this->getContainer()->getParameter('promo_won');
        $message = str_replace("[PROMO]", $promo->getName(), $message);

        return $message;
    }

    /**
     * Function that builds the string of message to use when wins points as a prize
     *
     * @param int $points points that the user won
     * @return string built message
     */
    private function getPrizePointsMessage($points) {
        $message = $this->getContainer()->getParameter('promo_points_message');
        $message = str_replace("[POINTS]", $points, $message);

        return $message;
    }

    /**
     * Builds string of message when the prize is specific
     *
     * @param string $specific string to go in prize
     * @param string $promo name of the promo to include in message
     * @return string built message
     */
    private function getSpecificPrizeMessage($specific, $promo) {
        $message = $this->getContainer()->getParameter('promo_specific_prize');
        $message = str_replace("[PRIZE]", $specific, $message);
        $message = str_replace("[PROMO]", $promo, $message);

        return $message;
    }
}