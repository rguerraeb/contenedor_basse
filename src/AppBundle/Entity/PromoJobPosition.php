<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoJobPosition
 */
class PromoJobPosition
{
    /**
     * @var integer
     */
    private $promoJobPositionId;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\JobPosition
     */
    private $jobPosition;


    /**
     * Get promoJobPositionId
     *
     * @return integer 
     */
    public function getPromoJobPositionId()
    {
        return $this->promoJobPositionId;
    }

    /**
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoJobPosition
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
     * Set jobPosition
     *
     * @param \AppBundle\Entity\JobPosition $jobPosition
     * @return PromoJobPosition
     */
    public function setJobPosition(\AppBundle\Entity\JobPosition $jobPosition = null)
    {
        $this->jobPosition = $jobPosition;

        return $this;
    }

    /**
     * Get jobPosition
     *
     * @return \AppBundle\Entity\JobPosition 
     */
    public function getJobPosition()
    {
        return $this->jobPosition;
    }
}
