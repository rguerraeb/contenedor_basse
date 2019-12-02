<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Helper\SessionHelper;

class MessageSendCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "app/console")
            ->setName('message:send')

            // the short description shown while running "php app/console list"
            ->setDescription('Sends message id from what is defined in message_log')

            ->addArgument('messageId', InputArgument::REQUIRED, 'Id of message')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("Receives message.message_id and sends SMS to staff in message_log with this message and status = 0")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln(array(
            'Send SMS ',
            '============',
            ''
        ));

        // outputs a message followed by a "\n"
        $messageId = $input->getArgument('messageId');
        $output->writeln('Received message.message_id = ' . $messageId);

        // Getting message Log
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        try {
            $messageLogs = $em->getRepository('AppBundle:MessageLog')
                ->findBy(
                    array(
                        'message' => $em->getReference('AppBundle\Entity\Message', $messageId),
                        'messageLogStatus' => 1
                    )
                );
        } catch (\Exception $e) {
            // Error
            $output->writeln("<error> Invalid message.message_id</error>");
            return;
        }

        // Create helper to send SMS
        $helper = new SessionHelper();

        // Send to each staff and change
        foreach ($messageLogs as $messageLog) {
            // Get phone
            $phone = $messageLog->getPhone();

            // Get message
            $message = $messageLog->getMessage()->getSms();

            $output->writeln(array(
                'Sending...',
                '    Message: "' . $message . '"',
                '    Receiver: "' . $phone . '"'
            ));

            // Send sms
            // TODO: Obtener el token de parameters
            $token = "Nzk0Mjc5NjE=";
            $response = $helper->send_sms_televida($phone, $message, $token);

            $output->writeln(array(
                'Response: ',
                '    Phone: "' . $phone . '"',
                '    Response: "' . $response . '"'
            ));
            
            // Read response
            $status = 3;
            $responseObj = $helper->findJSONObject($response);
            if ($responseObj) {
                if (isset($responseObj->resultCode)) {
                    // Read status
                    if ($responseObj->resultCode == 0) {
                        // Success sending SMS
                        $status = 2;
                    }
                }
            }

            $messageLog->setMessageLogStatus(
                $em->getReference(
                    'AppBundle:MessageLogStatus',
                    $status
                )
            );
            $em->persist($messageLog);
            $em->flush();
        }
    }
}