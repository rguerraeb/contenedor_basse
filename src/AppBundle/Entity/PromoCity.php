<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCity
 */
class PromoCity
{
    /**
     * @var integer
     */
    private $promoCityId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\City
     */
    private $city;


    /**
     * Get promoCityId
     *
     * @return integer 
     */
    public function getPromoCityId()
    {
        return $this->promoCityId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoCity
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
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     * @return PromoCity
     */
    public function setCity(\AppBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
}
