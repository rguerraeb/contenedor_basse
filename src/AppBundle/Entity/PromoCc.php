<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PromoCc
 */
class PromoCc
{
    /**
     * @var integer
     */
    private $promoCcId;

    /**
     * @var string
     */
    private $cc;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;


    /**
     * Get promoCcId
     *
     * @return integer 
     */
    public function getPromoCcId()
    {
        return $this->promoCcId;
    }

    /**
     * Set cc
     *
     * @param string $cc
     * @return PromoCc
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
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return PromoCc
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
}
