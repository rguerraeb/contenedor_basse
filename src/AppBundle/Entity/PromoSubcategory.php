<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoSubcategory
 */
class PromoSubcategory
{
    /**
     * @var integer
     */
    private $promoSubcategoryId;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\PromoCategory
     */
    private $parentCategory;

    /**
     * @var \AppBundle\Entity\PromoCategory
     */
    private $subcategory;


    /**
     * Get promoSubcategoryId
     *
     * @return integer 
     */
    public function getPromoSubcategoryId()
    {
        return $this->promoSubcategoryId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PromoSubcategory
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
     * Set parentCategory
     *
     * @param \AppBundle\Entity\PromoCategory $parentCategory
     * @return PromoSubcategory
     */
    public function setParentCategory(\AppBundle\Entity\PromoCategory $parentCategory = null)
    {
        $this->parentCategory = $parentCategory;

        return $this;
    }

    /**
     * Get parentCategory
     *
     * @return \AppBundle\Entity\PromoCategory 
     */
    public function getParentCategory()
    {
        return $this->parentCategory;
    }

    /**
     * Set subcategory
     *
     * @param \AppBundle\Entity\PromoCategory $subcategory
     * @return PromoSubcategory
     */
    public function setSubcategory(\AppBundle\Entity\PromoCategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \AppBundle\Entity\PromoCategory 
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }
}
