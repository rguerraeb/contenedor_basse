<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageLog
 */
class MessageLog
{
    
    /**
     * @var integer
     */
    private $messageLogId;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\Message
     */
    private $message;

    /**
     * @var \AppBundle\Entity\MessageLogStatus
     */
    private $messageLogStatus;

    /**
     * @var \AppBundle\Entity\MessageBag
     */
    private $messageBag;

    /**
     * Get messageLogId
     *
     * @return integer 
     */
    public function getMessageLogId()
    {
        return $this->messageLogId;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return MessageLog
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return MessageLog
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
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return MessageLog
     */
    public function setStaff(\AppBundle\Entity\Staff $staff = null)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return \AppBundle\Entity\Staff 
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set message
     *
     * @param \AppBundle\Entity\Message $message
     * @return MessageLog
     */
    public function setMessage(\AppBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \AppBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set messageLogStatus
     *
     * @param \AppBundle\Entity\MessageLogStatus $messageLogStatus
     * @return MessageLog
     */
    public function setMessageLogStatus(\AppBundle\Entity\MessageLogStatus $messageLogStatus = null)
    {
        $this->messageLogStatus = $messageLogStatus;

        return $this;
    }

    /**
     * Get messageLogStatus
     *
     * @return \AppBundle\Entity\MessageLogStatus 
     */
    public function getMessageLogStatus()
    {
        return $this->messageLogStatus;
    }

    /**
     * Set messageBag
     *
     * @param \AppBundle\Entity\MessageBag $messageBag
     * @return MessageLog
     */
    public function setMessageBag(\AppBundle\Entity\MessageBag $messageBag = null)
    {
        $this->messageBag = $messageBag;

        return $this;
    }

    /**
     * Get messageBag
     *
     * @return \AppBundle\Entity\MessageBag 
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }
}
