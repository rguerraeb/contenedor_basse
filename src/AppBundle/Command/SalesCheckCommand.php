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

class SalesCheckCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            // the name of the command (the part after "app/console")
            ->setName('sales:check')

            // the short description shown while running "php app/console list"
            ->setDescription('Checks if staff sales are equal to the staff sales of inferior hierarchy')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Just use sale:check")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // Start output
        $output->writeln(array(
            'Sales Check ',
            '============',
            ''
        ));

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $output->writeln('Look for staffs...');
        $staffs = $em->getRepository('AppBundle:Staff')
            ->findBy(
                array(),
                array(
                    'jobPosition' => 'ASC'
                )
            );

        $totalS = sizeof($staffs);
        $output->writeln('Found ' . $totalS . ' staff(s)...');

        $minMonth = 1;
        $maxMonth = 12;

        // Create file
        $dir = $this->getContainer()->get('kernel')->getRootDir()
            . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
        $fileName = $dir . 'sales_check_' . date('Y-m-d_H_i_s') . '.csv';
        $file = fopen($fileName, 'w');
        fputcsv($file, array(
            'staff_id', 'root_job_position', 'root_sales', 'first_branch_sales', 'first_branch_staff_ids'
        ));

        $output->writeln('Created file: ' . $fileName);

        $staffCount = 1;
        foreach ($staffs as $staff) {
            $staffId = $staff->getStaffId();
            $jobPositionId = $staff->getJobPosition()->getId();

            $output->writeln(array(
                '<fg=yellow>||------------------||</>',
                'Staff id. ' . $staffId . ' | (' . $staffCount . '/' . $totalS . ')'
            ));

            if ($jobPositionId != 5 && $jobPositionId != 6) {
                // Get it's sales
                $sales = $em->getRepository ( "AppBundle:Staff" )
                    ->getPointsForPlot (
                        array(
                            $staffId
                        ),
                        $staffId,
                        $minMonth,
                        $maxMonth
                    );

                // Get their children
                $tree = $em
                    ->getRepository('AppBundle:Staff')
                    ->findLowerJobPosition($staff, array($jobPositionId));

                $lowerIds = $this->idsFromTree($tree);

                // Get lower staffs sales
                $theirSales = $em->getRepository ("AppBundle:Staff")
                    ->getPointsForPlot(
                        $lowerIds,
                        $staffId,
                        $minMonth,
                        $maxMonth
                    );

                // Get totals
                $mine = $this->yearSales($sales);
                $theirs = $this->yearSales($theirSales);

                $output->writeln(array(
                    'Result:',
                    '    Staff No. ' . $staffId . ', sales: ' . $mine,
                    '    Staff Nos. ' . join(', ', $lowerIds) . ', sales: ' . $theirs
                ));

                if ($mine != $theirs) {
                    fputcsv($file, array(
                        $staffId, $jobPositionId, $mine, $theirs, join(', ', $lowerIds)
                    ));
                }
            }

            $staffCount++;
        }
    }

    /**
     * From the tree of staffs found below, make an array of just their staffId
     *
     * @param array $tree staffs under the hierarchy of a staff
     * @return array staffIds found in tree
     */
    private function idsFromTree($tree) {
        $ids = array();
        foreach ($tree as $staff) {
            // Append staff id
            array_push($ids, $staff['staffId']);
        }

        return $ids;
    }

    /**
     * Get total sales for whole year
     *
     * @param array $monthlySales sales separated by month
     * @return int sum of sales
     */
    private function yearSales($monthlySales) {
        $sum = 0;
        foreach ($monthlySales as $monthlySale) {
            // Append staff id
            $sum += (int) $monthlySale['total_ventas'];
        }

        return $sum;
    }
}