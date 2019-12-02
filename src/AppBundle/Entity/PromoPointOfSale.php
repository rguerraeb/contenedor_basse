<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoPointOfSale
 */
class PromoPointOfSale
{
    /**
     * @var integer
     */
    private $promoPointOfSaleId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\PointOfSale
     */
    private $pointOfSale;


    /**
     * Get promoPointOfSaleId
     *
     * @return integer 
     */
    public function getPromoPointOfSaleId()
    {
        return $this->promoPointOfSaleId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoPointOfSale
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
     * Set pointOfSale
     *
     * @param \AppBundle\Entity\PointOfSale $pointOfSale
     * @return PromoPointOfSale
     */
    public function setPointOfSale(\AppBundle\Entity\PointOfSale $pointOfSale = null)
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

    /**
     * Get pointOfSale
     *
     * @return \AppBundle\Entity\PointOfSale 
     */
    public function getPointOfSale()
    {
        return $this->pointOfSale;
    }
}
