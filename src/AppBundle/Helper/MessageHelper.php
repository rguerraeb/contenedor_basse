<?php
namespace AppBundle\Helper;

class MessageHelper
{
    private $rootDir;
    private $em;
    private $env;

    public function __construct(\Doctrine\ORM\EntityManager $em, $rootDir, $env)
    {
        $this->rootDir = $rootDir;
        $this->em = $em;
        $this->env = $env;
    }

    /**
     * Sends the SMS to all the staffs that are assigned in background with exec nohup
     *
     * @param Message $message message to send
     */
    public function backgroundSendMessage($message) {
        // Start sub-process to send SMS
        // Root dir gets us until in project app folder
        $rootDir = $this->rootDir;
        
        // Command to execute, an in project command
        $command = "message:send " . $message->getMessageId();
        $allCommand = $rootDir . DIRECTORY_SEPARATOR . "console " . $command;
        $logFile = $rootDir . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'message_send.log';

        // Add enviorment if necessary
        if ($this->env != 'dev') {
            // Add it's enviorment
            $allCommand .= ' --env=' . $this->env;
        }

        // Process as background
        exec('/usr/local/bin/php ' . $allCommand . ' > ' . $logFile . ' 2>&1 &');
    }

    /**
     * Marks message as 'ENVIADO'
     *
     * @param Message $message message to change status
     * @return Message updated entity
     */
    public function sentStatus ($message) {
        // Mark message as sent
        $message->setMessageStatus(
            $this->em->getReference('AppBundle:MessageStatus', 4)
        );

        $this->em->persist($message);
        $this->em->flush();
    }
}
?>