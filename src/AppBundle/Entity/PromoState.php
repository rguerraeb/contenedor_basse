<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoState
 */
class PromoState
{
    /**
     * @var integer
     */
    private $promoStateId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\State
     */
    private $state;


    /**
     * Get promoStateId
     *
     * @return integer 
     */
    public function getPromoStateId()
    {
        return $this->promoStateId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoState
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
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     * @return PromoState
     */
    public function setState(\AppBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State 
     */
    public function getState()
    {
        return $this->state;
    }
}
