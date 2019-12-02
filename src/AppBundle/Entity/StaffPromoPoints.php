<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StaffPromoPoints
 */
class StaffPromoPoints
{
    /**
     * @var integer
     */
    private $staffPromoPointsId;

    /**
     * @var float
     */
    private $points;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Staff
     */
    private $staff;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;
    
    private $skuCategory;
    
    private $sku;


    /**
     * @return mixed
     */
    public function getSkuCategory ()
    {
        return $this->skuCategory;
    }

    /**
     * @return mixed
     */
    public function getSku ()
    {
        return $this->sku;
    }

    /**
     * @param mixed $skuCategory
     */
    public function setSkuCategory ($skuCategory)
    {
        $this->skuCategory = $skuCategory;
        return $this;
    }

    /**
     * @param mixed $sku
     */
    public function setSku ($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Get staffPromoPointsId
     *
     * @return integer 
     */
    public function getStaffPromoPointsId()
    {
        return $this->staffPromoPointsId;
    }

    /**
     * Set points
     *
     * @param float $points
     * @return StaffPromoPoints
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
     * @return StaffPromoPoints
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
     * @return StaffPromoPoints
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
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return StaffPromoPoints
     */
    public function setPromo(\AppBundle\Entity\Promo $promo = null)
    {
        $this->promo = $promo;
    
        return $this;
    }

    /**
     * Get promo
     *
     * @return \AppBundle\Entity\Promo 
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return StaffPromoPoints
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
