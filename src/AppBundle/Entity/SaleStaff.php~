<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SaleStaff
 */
class SaleStaff
{
    /**
     * @var integer
     */
    private $saleStaffId;

    /**
     * @var integer
     */
    private $points;

    /**
     * @var string
     */
    private $smsString;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var integer
     */
    private $createdBy;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;

    /**
     * @var \AppBundle\Entity\Sku
     */
    private $sku;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var integer
     */
    private $isCancelled;

    /**
     * @var \AppBundle\Entity\SaleStaff
     */
    private $originalSaleStaff;


    /**
     * Get saleStaffId
     *
     * @return integer 
     */
    public function getSaleStaffId()
    {
        return $this->saleStaffId;
    }

    /**
     * Set points
     *
     * @param integer $points
     * @return SaleStaff
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return integer 
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set smsString
     *
     * @param string $smsString
     * @return SaleStaff
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SaleStaff
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
     * @param integer $createdBy
     * @return SaleStaff
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return SaleStaff
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
     * Set sku
     *
     * @param \AppBundle\Entity\Sku $sku
     * @return SaleStaff
     */
    public function setSku(\AppBundle\Entity\Sku $sku = null)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return \AppBundle\Entity\Sku 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set staff
     *
     * @param \AppBundle\Entity\Staff $staff
     * @return SaleStaff
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
     * @var boolean
     */
    private $wasSeller;


    /**
     * Set wasSeller
     *
     * @param boolean $wasSeller
     * @return SaleStaff
     */
    public function setWasSeller($wasSeller)
    {
        $this->wasSeller = $wasSeller;

        return $this;
    }

    /**
     * Get wasSeller
     *
     * @return boolean 
     */
    public function getWasSeller()
    {
        return $this->wasSeller;
    }

    /**
     * Set isCancelled
     *
     * @param integer $isCancelled
     * @return SaleStaff
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

    /**
     * Set originalSaleStaff
     *
     * @param \AppBundle\Entity\SaleStaff $originalSaleStaff
     * @return SaleStaff
     */
    public function setOriginalSaleStaff(\AppBundle\Entity\SaleStaff $originalSaleStaff = null)
    {
        $this->originalSaleStaff = $originalSaleStaff;

        return $this;
    }

    /**
     * Get originalSaleStaff
     *
     * @return \AppBundle\Entity\SaleStaff 
     */
    public function getOriginalSaleStaff()
    {
        return $this->originalSaleStaff;
    }
}
