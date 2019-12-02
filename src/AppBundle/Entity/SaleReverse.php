<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SaleReverse
 */
class SaleReverse
{
    /**
     * @var integer
     */
    private $saleReverseId;

    /**
     * @var string
     */
    private $skuCode;

    /**
     * @var string
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get saleReverseId
     *
     * @return integer 
     */
    public function getSaleReverseId()
    {
        return $this->saleReverseId;
    }

    /**
     * Set skuCode
     *
     * @param string $skuCode
     * @return SaleReverse
     */
    public function setSkuCode($skuCode)
    {
        $this->skuCode = $skuCode;
    
        return $this;
    }

    /**
     * Get skuCode
     *
     * @return string 
     */
    public function getSkuCode()
    {
        return $this->skuCode;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return SaleReverse
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SaleReverse
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
}
