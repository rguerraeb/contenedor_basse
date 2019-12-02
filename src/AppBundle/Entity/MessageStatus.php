<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageStatus
 */
class MessageStatus
{
    /**
     * @var integer
     */
    private $messageStatusId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get messageStatusId
     *
     * @return integer 
     */
    public function getMessageStatusId()
    {
        return $this->messageStatusId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MessageStatus
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
