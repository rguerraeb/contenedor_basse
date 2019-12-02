<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffPoints
 */
class StaffPoints
{
    /**
     * @var integer
     */
    private $staffPointsId;

    /**
     * @var float
     */
    private $point;

    /**
     * @var string
     */
    private $exchangeChannel;

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;


    /**
     * Get staffPointsId
     *
     * @return integer 
     */
    public function getStaffPointsId()
    {
        return $this->staffPointsId;
    }

    /**
     * Set point
     *
     * @param float $point
     * @return StaffPoints
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return float 
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set exchangeChannel
     *
     * @param string $exchangeChannel
     * @return StaffPoints
     */
    public function setExchangeChannel($exchangeChannel)
    {
        $this->exchangeChannel = $exchangeChannel;

        return $this;
    }

    /**
     * Get exchangeChannel
     *
     * @return string 
     */
    public function getExchangeChannel()
    {
        return $this->exchangeChannel;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return StaffPoints
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffPoints
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
     * @return StaffPoints
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
}
