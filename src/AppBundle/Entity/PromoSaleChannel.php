<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoSaleChannel
 */
class PromoSaleChannel
{
    /**
     * @var integer
     */
    private $promoSaleChannelId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\SaleChannel
     */
    private $saleChannel;


    /**
     * Get promoSaleChannelId
     *
     * @return integer 
     */
    public function getPromoSaleChannelId()
    {
        return $this->promoSaleChannelId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoSaleChannel
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
     * Set saleChannel
     *
     * @param \AppBundle\Entity\SaleChannel $saleChannel
     * @return PromoSaleChannel
     */
    public function setSaleChannel(\AppBundle\Entity\SaleChannel $saleChannel = null)
    {
        $this->saleChannel = $saleChannel;

        return $this;
    }

    /**
     * Get saleChannel
     *
     * @return \AppBundle\Entity\SaleChannel 
     */
    public function getSaleChannel()
    {
        return $this->saleChannel;
    }
}
