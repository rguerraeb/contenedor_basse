<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedeemPointsDetails
 */
class RedeemPointsDetails
{
    /**
     * @var integer
     */
    private $redeemPointsDetailsId;

    /**
     * @var float
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $staffId;

    /**
     * @var \AppBundle\Entity\PointType
     */
    private $pointType;

    /**
     * @var \AppBundle\Entity\PrizeExchange
     */
    private $prizeExchange;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;


    /**
     * Get redeemPointsDetailsId
     *
     * @return integer 
     */
    public function getRedeemPointsDetailsId()
    {
        return $this->redeemPointsDetailsId;
    }

    /**
     * Set points
     *
     * @param float $points
     * @return RedeemPointsDetails
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return float 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RedeemPointsDetails
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
     * Set staffId
     *
     * @param integer $staffId
     * @return RedeemPointsDetails
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
     * Set pointType
     *
     * @param \AppBundle\Entity\PointType $pointType
     * @return RedeemPointsDetails
     */
    public function setPointType(\AppBundle\Entity\PointType $pointType = null)
    {
        $this->pointType = $pointType;

        return $this;
    }

    /**
     * Get pointType
     *
     * @return \AppBundle\Entity\PointType 
     */
    public function getPointType()
    {
        return $this->pointType;
    }

    /**
     * Set prizeExchange
     *
     * @param \AppBundle\Entity\PrizeExchange $prizeExchange
     * @return RedeemPointsDetails
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

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return RedeemPointsDetails
     */
    public function setSale(\AppBundle\Entity\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \AppBundle\Entity\Sale 
     */
    public function getSale()
    {
        return $this->sale;
    }
}
