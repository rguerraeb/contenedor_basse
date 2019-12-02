<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageBag
 */
class MessageBag
{
    /**
     * @var integer
     */
    private $messageBagId;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $quantity;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $available;


    /**
     * Get messageBagId
     *
     * @return integer 
     */
    public function getMessageBagId()
    {
        return $this->messageBagId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return MessageBag
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
     * Set quantity
     *
     * @param integer $quantity
     * @return MessageBag
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MessageBag
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set available
     *
     * @param integer $available
     * @return MessageBag
     */
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get available
     *
     * @return integer 
     */
    public function getAvailable()
    {
        return $this->available;
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
