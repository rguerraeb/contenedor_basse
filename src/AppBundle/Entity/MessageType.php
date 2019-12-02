<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageType
 */
class MessageType
{
    /**
     * @var integer
     */
    private $messageTypeId;

    /**
     * @var string
     */
    private $name;


    /**
     * Get messageTypeId
     *
     * @return integer 
     */
    public function getMessageTypeId()
    {
        return $this->messageTypeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MessageType
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
