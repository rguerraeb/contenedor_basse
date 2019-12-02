<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message
{
    
    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var string
     */
    private $sms;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\User
     */
    private $createdBy;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $sendDate;

    /**
     * @var \AppBundle\Entity\MessageType
     */
    private $messageType;

    /**
     * @var \AppBundle\Entity\MessageStatus
     */
    private $messageStatus;


    /**
     * Get messageId
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set sms
     *
     * @param string $sms
     * @return Message
     */
    public function setSms($sms)
    {
        $this->sms = $sms;

        return $this;
    }

    /**
     * Get sms
     *
     * @return string 
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Message
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
     * Set createdBy
     *
     * @param \AppBundle\Entity\User $createdBy
     * @return Message
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \AppBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Message
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
     * Set sendDate
     *
     * @param \DateTime $sendDate
     * @return Message
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;

        return $this;
    }

    /**
     * Get sendDate
     *
     * @return \DateTime 
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * Set messageType
     *
     * @param \AppBundle\Entity\MessageType $messageType
     * @return Message
     */
    public function setMessageType(\AppBundle\Entity\MessageType $messageType = null)
    {
        $this->messageType = $messageType;

        return $this;
    }

    /**
     * Get messageType
     *
     * @return \AppBundle\Entity\MessageType 
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * Set messageStatus
     *
     * @param \AppBundle\Entity\MessageStatus $messageStatus
     * @return Message
     */
    public function setMessageStatus(\AppBundle\Entity\MessageStatus $messageStatus = null)
    {
        $this->messageStatus = $messageStatus;

        return $this;
    }

    /**
     * Get messageStatus
     *
     * @return \AppBundle\Entity\MessageStatus 
     */
    public function getMessageStatus()
    {
        return $this->messageStatus;
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
