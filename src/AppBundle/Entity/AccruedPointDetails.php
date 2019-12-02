<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccruedPointDetails
 */
class AccruedPointDetails
{
    /**
     * @var integer
     */
    private $accruedPointDetailsId;

    /**
     * @var float
     */
    private $accruedPoints;

    /**
     * @var float
     */
    private $availablePoints;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\PointType
     */
    private $pointType;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;


    /**
     * Get accruedPointDetailsId
     *
     * @return integer 
     */
    public function getAccruedPointDetailsId()
    {
        return $this->accruedPointDetailsId;
    }

    /**
     * Set accruedPoints
     *
     * @param float $accruedPoints
     * @return AccruedPointDetails
     */
    public function setAccruedPoints($accruedPoints)
    {
        $this->accruedPoints = $accruedPoints;

        return $this;
    }

    /**
     * Get accruedPoints
     *
     * @return float 
     */
    public function getAccruedPoints()
    {
        return $this->accruedPoints;
    }

    /**
     * Set availablePoints
     *
     * @param float $availablePoints
     * @return AccruedPointDetails
     */
    public function setAvailablePoints($availablePoints)
    {
        $this->availablePoints = $availablePoints;

        return $this;
    }

    /**
     * Get availablePoints
     *
     * @return float 
     */
    public function getAvailablePoints()
    {
        return $this->availablePoints;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return AccruedPointDetails
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
     * Set pointType
     *
     * @param \AppBundle\Entity\PointType $pointType
     * @return AccruedPointDetails
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
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return AccruedPointDetails
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

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return AccruedPointDetails
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
