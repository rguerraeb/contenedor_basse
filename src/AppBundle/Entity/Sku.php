<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sku
 */
class Sku
{
    /**
     * @var integer
     */
    private $skuId;

    /**
     * @var integer
     */
    private $skuCategoryId;

    /**
     * @var string
     */
    private $skuFilterString;

    /**
     * @var integer
     */
    private $rewardPoint;

    /**
     * @var string
     */
    private $brand;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $cc;

    /**
     * @var string
     */
    private $year;

    /**
     * @var string
     */
    private $color;

    /**
     * @var integer
     */
    private $createdBy;

    /**
     * @var \DateTime
     */
    private $createdAt;


    /**
     * Get skuId
     *
     * @return integer 
     */
    public function getSkuId()
    {
        return $this->skuId;
    }

    /**
     * Set skuCategoryId
     *
     * @param integer $skuCategoryId
     * @return Sku
     */
    public function setSkuCategoryId($skuCategoryId)
    {
        $this->skuCategoryId = $skuCategoryId;

        return $this;
    }

    /**
     * Get skuCategoryId
     *
     * @return integer 
     */
    public function getSkuCategoryId()
    {
        return $this->skuCategoryId;
    }

    /**
     * Set skuFilterString
     *
     * @param string $skuFilterString
     * @return Sku
     */
    public function setSkuFilterString($skuFilterString)
    {
        $this->skuFilterString = $skuFilterString;

        return $this;
    }

    /**
     * Get skuFilterString
     *
     * @return string 
     */
    public function getSkuFilterString()
    {
        return $this->skuFilterString;
    }

    /**
     * Set rewardPoint
     *
     * @param integer $rewardPoint
     * @return Sku
     */
    public function setRewardPoint($rewardPoint)
    {
        $this->rewardPoint = $rewardPoint;

        return $this;
    }

    /**
     * Get rewardPoint
     *
     * @return integer 
     */
    public function getRewardPoint()
    {
        return $this->rewardPoint;
    }

    /**
     * Set brand
     *
     * @param string $brand
     * @return Sku
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return Sku
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set cc
     *
     * @param string $cc
     * @return Sku
     */
    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * Get cc
     *
     * @return string 
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set year
     *
     * @param string $year
     * @return Sku
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Sku
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Sku
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Sku
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
     * @var \AppBundle\Entity\SkuCategory
     */
    private $skuCategory;


    /**
     * Set skuCategory
     *
     * @param \AppBundle\Entity\SkuCategory $skuCategory
     * @return Sku
     */
    public function setSkuCategory(\AppBundle\Entity\SkuCategory $skuCategory = null)
    {
        $this->skuCategory = $skuCategory;

        return $this;
    }

    /**
     * Get skuCategory
     *
     * @return \AppBundle\Entity\SkuCategory 
     */
    public function getSkuCategory()
    {
        return $this->skuCategory;
    }

    /**
     * Get toString
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->skuFilterString;
    }
}
