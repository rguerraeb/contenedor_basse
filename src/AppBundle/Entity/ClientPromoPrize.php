<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPromoPrize
 */
class ClientPromoPrize
{
    /**
     * @var integer
     */
    private $clientPromoPrizeId;

    /**
     * @var string
     */
    private $clientPhone;

    /**
     * @var string
     */
    private $message;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \AppBundle\Entity\Promo
     */
    private $promo;

    /**
     * @var \AppBundle\Entity\Prize
     */
    private $prize;

    /**
     * @var \AppBundle\Entity\ClientPromoPrizeStatus
     */
    private $clientPromoPrizeStatus;

    /**
     * @var \AppBundle\Entity\Sale
     */
    private $sale;

    /**
     * @var \AppBundle\Entity\PromoPrize
     */
    private $promoPrize;


    /**
     * Get clientPromoPrizeId
     *
     * @return integer 
     */
    public function getClientPromoPrizeId()
    {
        return $this->clientPromoPrizeId;
    }

    /**
     * Set clientPhone
     *
     * @param string $clientPhone
     * @return ClientPromoPrize
     */
    public function setClientPhone($clientPhone)
    {
        $this->clientPhone = $clientPhone;

        return $this;
    }

    /**
     * Get clientPhone
     *
     * @return string 
     */
    public function getClientPhone()
    {
        return $this->clientPhone;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return ClientPromoPrize
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return ClientPromoPrize
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
     * Set promo
     *
     * @param \AppBundle\Entity\Promo $promo
     * @return ClientPromoPrize
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
     * Set prize
     *
     * @param \AppBundle\Entity\Prize $prize
     * @return ClientPromoPrize
     */
    public function setPrize(\AppBundle\Entity\Prize $prize = null)
    {
        $this->prize = $prize;

        return $this;
    }

    /**
     * Get prize
     *
     * @return \AppBundle\Entity\Prize 
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * Set clientPromoPrizeStatus
     *
     * @param \AppBundle\Entity\ClientPromoPrizeStatus $clientPromoPrizeStatus
     * @return ClientPromoPrize
     */
    public function setClientPromoPrizeStatus(\AppBundle\Entity\ClientPromoPrizeStatus $clientPromoPrizeStatus = null)
    {
        $this->clientPromoPrizeStatus = $clientPromoPrizeStatus;

        return $this;
    }

    /**
     * Get clientPromoPrizeStatus
     *
     * @return \AppBundle\Entity\ClientPromoPrizeStatus 
     */
    public function getClientPromoPrizeStatus()
    {
        return $this->clientPromoPrizeStatus;
    }

    /**
     * Set sale
     *
     * @param \AppBundle\Entity\Sale $sale
     * @return ClientPromoPrize
     */
    public function setSale(\AppBundle\Entity\Sale $sale = null)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return \AppBundle\Entity\Sale 
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set promoPrize
     *
     * @param \AppBundle\Entity\PromoPrize $promoPrize
     * @return ClientPromoPrize
     */
    public function setPromoPrize(\AppBundle\Entity\PromoPrize $promoPrize = null)
    {
        $this->promoPrize = $promoPrize;

        return $this;
    }

    /**
     * Get promoPrize
     *
     * @return \AppBundle\Entity\PromoPrize 
     */
    public function getPromoPrize()
    {
        return $this->promoPrize;
    }
}
