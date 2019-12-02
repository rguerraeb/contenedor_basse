<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class StaffPasswordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('staff:password-update')

            // the short description shown while running "php app/console list"
            ->setDescription('Encripts citizenId as password')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command will take the staff citizenId, encript it and save it as password")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(array(
            'Staff password update',
            '============',
            ''
        ));

        // outputs a message followed by a "\n"
        $output->writeln('Getting Staffs');

        // Getting staffs
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $staffs = $em->getRepository('AppBundle:Staff')->findAll();

        // outputs a message without adding a "\n" at the end of the line
        $total = 0;
        if ($staffs) {
            $total = sizeof($staffs);
        }

        $upCont = 0;
        foreach ($staffs as $key => $staff) {
            $output->write('Updating staff: ');
            $output->write($staff->getName());
            $output->writeln('...');

            // Try to update
            // Helper to enconde password
            $staffHelper = $this->getContainer()->get('user.helper');
            try {
                // Update
                $encoded = $staffHelper->encodePassword($staff, $staff->getCitizenId());
                $staff->setPasswd($encoded);

                $em->persist($staff);
                $em->flush();
                $upCont++;

                // Notify user
                $output->writeln('<info>Updated</info>');
            } catch (\Exception $e) {
                // Error
                $output->writeln("<error> Exception updating: $staff->getName()</error>");
            }
        }

        // outputs a message followed by a "\n"
        $output->writeln('-----------------');
        $output->writeln('<info>Finish...');
        $output->writeln("Updated: $upCont, Total staffs: $total</info>");
    }
}