<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrizeExchange
 */
class PrizeExchange
{
    /**
     * @var integer
     */
    private $prizeExchangeId;

    /**
     * @var float
     */
    private $redeemPoints;

    /**
     * @var string
     */
    private $channelExchange;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;
    
    private $capturePointsAvailable;
    
    


    /**
     * @return mixed
     */
    public function getCapturePointsAvailable ()
    {
        return $this->capturePointsAvailable;
    }

    /**
     * @param mixed $capturePointsAvailable
     */
    public function setCapturePointsAvailable ($capturePointsAvailable)
    {
        $this->capturePointsAvailable = $capturePointsAvailable;
        
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
     * Set redeemPoints
     *
     * @param float $redeemPoints
     * @return PrizeExchange
     */
    public function setRedeemPoints($redeemPoints)
    {
        $this->redeemPoints = $redeemPoints;

        return $this;
    }

    /**
     * Get redeemPoints
     *
     * @return float 
     */
    public function getRedeemPoints()
    {
        return $this->redeemPoints;
    }

    /**
     * Set channelExchange
     *
     * @param string $channelExchange
     * @return PrizeExchange
     */
    public function setChannelExchange($channelExchange)
    {
        $this->channelExchange = $channelExchange;

        return $this;
    }

    /**
     * Get channelExchange
     *
     * @return string 
     */
    public function getChannelExchange()
    {
        return $this->channelExchange;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PrizeExchange
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
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return PrizeExchange
     */
    public function setPrize(\AppBundle\Entity\Prize $prize = null)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return \AppBundle\Entity\Prize 
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return PrizeExchange
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
