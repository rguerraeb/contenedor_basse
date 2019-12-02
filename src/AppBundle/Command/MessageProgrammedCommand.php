<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Helper\SessionHelper;

class MessageProgrammedCommand extends ContainerAwareCommand
{
    // Quantity of minutes to search in
    private $interval = 1;

    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('message:programmed')

            // the short description shown while running "php app/console list"
            ->setDescription('Checks for programmed messages to send')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Checks to see if current time is equal to send_date in any message. If so, start sending this messages")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(array(
            'Send Programmed SMS ',
            '============',
            ''
        ));

        // outputs a message followed by a "\n"
        $now = new \DateTime();
        $start = $now->format('Y-m-d H:i:00');

        // Interval end
        $endD = new \DateTime();
        $endD->add(new \DateInterval('PT' . $this->interval . 'M'));
        $end = $endD->format('Y-m-d H:i:00');

        $output->writeln(
            'Looking for messages with send_date > '
            . $start . ' and send_date < '
            . $end
        );

        // Getting message Log
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        try {
            $messages = $em->getRepository('AppBundle:Message')
                ->between($start, $end);

            $totalMessages = sizeof($messages);
            $output->writeln('Found ' . $totalMessages . ' messages');

            // Start sending messages
            $messageHelper = $this->getApplication()->getKernel()->getContainer()->get('message.helper');

            $cont = 1;
            foreach ($messages as $message) {
                // Send each message
                $output->writeln('Sending message \'' . $message->getName() . "' ($cont, $totalMessages)...");
                $messageHelper->backgroundSendMessage($message);

                // Change message status to 'ENVIADO'
                $messageHelper->sentStatus($message);

                $cont++;
            }

        } catch (\Exception $e) {
            // Error
            $output->writeln("<error> Error while looking for messages</error>");
            return;
        }
    }
}