<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SmsIncoming
 */
class SmsIncoming
{
    /**
     * @var integer
     */
    private $smsIncomingId;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $smsString;

    /**
     * @var string
     */
    private $parseResult;

    /**
     * @var boolean
     */
    private $alreadyParsed;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var integer
     */
    private $isCancelled;


    /**
     * Get smsIncomingId
     *
     * @return integer 
     */
    public function getSmsIncomingId()
    {
        return $this->smsIncomingId;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return SmsIncoming
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
     * Set smsString
     *
     * @param string $smsString
     * @return SmsIncoming
     */
    public function setSmsString($smsString)
    {
        $this->smsString = $smsString;

        return $this;
    }

    /**
     * Get smsString
     *
     * @return string 
     */
    public function getSmsString()
    {
        return $this->smsString;
    }

    /**
     * Set parseResult
     *
     * @param string $parseResult
     * @return SmsIncoming
     */
    public function setParseResult($parseResult)
    {
        $this->parseResult = $parseResult;

        return $this;
    }

    /**
     * Get parseResult
     *
     * @return string 
     */
    public function getParseResult()
    {
        return $this->parseResult;
    }

    /**
     * Set alreadyParsed
     *
     * @param boolean $alreadyParsed
     * @return SmsIncoming
     */
    public function setAlreadyParsed($alreadyParsed)
    {
        $this->alreadyParsed = $alreadyParsed;

        return $this;
    }

    /**
     * Get alreadyParsed
     *
     * @return boolean 
     */
    public function getAlreadyParsed()
    {
        return $this->alreadyParsed;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SmsIncoming
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
     * @return SmsIncoming
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
     * Set isCancelled
     *
     * @param integer $isCancelled
     * @return SmsIncoming
     */
    public function setIsCancelled($isCancelled)
    {
        $this->isCancelled = $isCancelled;

        return $this;
    }

    /**
     * Get isCancelled
     *
     * @return integer 
     */
    public function getIsCancelled()
    {
        return $this->isCancelled;
    }
}
