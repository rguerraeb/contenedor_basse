<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoSku
 */
class PromoSku
{
    /**
     * @var integer
     */
    private $promoSkuId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\Sku
     */
    private $sku;


    /**
     * Get promoSkuId
     *
     * @return integer 
     */
    public function getPromoSkuId()
    {
        return $this->promoSkuId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoSku
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
     * Set sku
     *
     * @param \AppBundle\Entity\Sku $sku
     * @return PromoSku
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
}
