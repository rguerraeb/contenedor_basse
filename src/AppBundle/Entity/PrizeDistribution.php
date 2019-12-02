<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrizeDistribution
 */
class PrizeDistribution
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $prizeId;

    /**
     * @var \DateTime
     */
    private $dateActivate;

    /**
     * @var integer
     */
    private $amount;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prizeId
     *
     * @param integer $prizeId
     * @return PrizeDistribution
     */
    public function setPrizeId($prizeId)
    {
        $this->prizeId = $prizeId;

        return $this;
    }

    /**
     * Get prizeId
     *
     * @return integer 
     */
    public function getPrizeId()
    {
        return $this->prizeId;
    }

    /**
     * Set dateActivate
     *
     * @param \DateTime $dateActivate
     * @return PrizeDistribution
     */
    public function setDateActivate($dateActivate)
    {
        $this->dateActivate = $dateActivate;

        return $this;
    }

    /**
     * Get dateActivate
     *
     * @return \DateTime 
     */
    public function getDateActivate()
    {
        return $this->dateActivate;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return PrizeDistribution
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }
}
