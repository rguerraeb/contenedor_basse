<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffRedeemPromoPoints
 */
class StaffRedeemPromoPoints
{
    /**
     * @var integer
     */
    private $staffRedeemPromoPointsId;

    /**
     * @var integer
     */
    private $staffId;

    /**
     * @var integer
     */
    private $prizeExchangeId;

    /**
     * @var string
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get staffRedeemPromoPointsId
     *
     * @return integer 
     */
    public function getStaffRedeemPromoPointsId()
    {
        return $this->staffRedeemPromoPointsId;
    }

    /**
     * Set staffId
     *
     * @param integer $staffId
     * @return StaffRedeemPromoPoints
     */
    public function setStaffId($staffId)
    {
        $this->staffId = $staffId;
    
        return $this;
    }

    /**
     * Get staffId
     *
     * @return integer 
     */
    public function getStaffId()
    {
        return $this->staffId;
    }

    /**
     * Set prizeExchangeId
     *
     * @param integer $prizeExchangeId
     * @return StaffRedeemPromoPoints
     */
    public function setPrizeExchangeId($prizeExchangeId)
    {
        $this->prizeExchangeId = $prizeExchangeId;
    
        return $this;
    }

    /**
     * Get prizeExchangeId
     *
     * @return integer 
     */
    public function getPrizeExchangeId()
    {
        return $this->prizeExchangeId;
    }

    /**
     * Set points
     *
     * @param string $points
     * @return StaffRedeemPromoPoints
     */
    public function setPoints($points)
    {
        $this->points = $points;
    
        return $this;
    }

    /**
     * Get points
     *
     * @return string 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return StaffRedeemPromoPoints
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
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\PrizeExchange
     */
    private $prizeExchange;


    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return StaffRedeemPromoPoints
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
     * Set prizeExchange
     *
     * @param \AppBundle\Entity\PrizeExchange $prizeExchange
     * @return StaffRedeemPromoPoints
     */
    public function setPrizeExchange(\AppBundle\Entity\PrizeExchange $prizeExchange = null)
    {
        $this->prizeExchange = $prizeExchange;
    
        return $this;
    }

    /**
     * Get prizeExchange
     *
     * @return \AppBundle\Entity\PrizeExchange 
     */
    public function getPrizeExchange()
    {
        return $this->prizeExchange;
    }
}
