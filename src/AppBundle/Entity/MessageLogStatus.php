<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageLogStatus
 */
class MessageLogStatus
{
    /**
     * @var integer
     */
    private $messageLogStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get messageLogStatusId
     *
     * @return integer 
     */
    public function getMessageLogStatusId()
    {
        return $this->messageLogStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MessageLogStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * To string
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->name;
    }
}
