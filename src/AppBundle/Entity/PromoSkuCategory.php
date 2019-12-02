<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoSkuCategory
 */
class PromoSkuCategory
{
    /**
     * @var integer
     */
    private $promoSkuCategoryId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\SkuCategory
     */
    private $skuCategory;


    /**
     * Get promoSkuCategoryId
     *
     * @return integer 
     */
    public function getPromoSkuCategoryId()
    {
        return $this->promoSkuCategoryId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoSkuCategory
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
     * Set skuCategory
     *
     * @param \AppBundle\Entity\SkuCategory $skuCategory
     * @return PromoSkuCategory
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
}
